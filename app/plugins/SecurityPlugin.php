<?php

use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl\Adapter\Memory as AclList;

/**
 * SecurityPlugin
 *
 * This is the security plugin which controls that users only have access to the modules they're assigned to
 */
class SecurityPlugin extends Plugin
{
	/**
	 * Returns an existing or new access control list
	 *
	 * @returns AclList
	 */
	public function getAcl()
	{
		if (!isset($this->persistent->acl)) {

			$acl = new AclList();

			$acl->setDefaultAction(Acl::DENY);

			// Register roles
			$roles = [
				'users'  => new Role(
					'Users',
					'Member privileges, granted after sign in.'
				),
				'guests' => new Role(
					'Guests',
					'Anyone browsing the site who is not signed in is considered to be a "Guest".'
				)
			];

			foreach ($roles as $role) {
				$acl->addRole($role);
			}

			//Private area resources
			$privateResources = [
				'areas'    => ['index', 'search', 'new', 'edit', 'save', 'create', 'delete'],
				'events'    => ['index', 'search', 'new', 'edit', 'save', 'create', 'delete'],
				'exhibitors'    => ['index','search', 'edit', 'delete'],
				'notereservations'    => ['index', 'search', 'new', 'edit', 'save', 'create', 'delete'],
				'reservations'    => ['index','search', 'edit', 'delete'],
				'reservationservices'    => ['index', 'search', 'new', 'edit', 'save', 'create', 'delete'],
				'services'    => ['index', 'search', 'new', 'edit', 'save', 'create', 'delete'],
				'statireservations'    => ['index', 'search', 'new', 'edit', 'save', 'create', 'delete'],
				'users'    => ['index', 'search', 'new', 'edit', 'save', 'create', 'delete'],
			];
			foreach ($privateResources as $resource => $actions) {
				$acl->addResource(new Resource($resource), $actions);
			}

			//Public area resources
			$publicResources = [
				'index'      => ['index'],
				'errors'     => ['show401', 'show404', 'show500'],
				'session'    => ['index', 'register', 'start', 'end'],
				'reservations'    => ['new', 'save', 'create'],
				'exhibitors'    => [ 'new', 'nuovo', 'validate', 'save', 'create'],
			];
			foreach ($publicResources as $resource => $actions) {
				$acl->addResource(new Resource($resource), $actions);
			}

			//Grant access to public areas to both users and guests
			foreach ($roles as $role) {
				foreach ($publicResources as $resource => $actions) {
					foreach ($actions as $action){
						$acl->allow($role->getName(), $resource, $action);
					}
				}
			}

			//Grant access to private area to role Users
			foreach ($privateResources as $resource => $actions) {
				foreach ($actions as $action){
					$acl->allow('Users', $resource, $action);
				}
			}

			//The acl is stored in session, APC would be useful here too
			$this->persistent->acl = $acl;
		}

		return $this->persistent->acl;
	}

	/**
	 * This action is executed before execute any action in the application
	 *
	 * @param Event $event
	 * @param Dispatcher $dispatcher
	 * @return bool
	 */
	public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
	{
		$auth = $this->session->get('auth');
		if (!$auth){
			$role = 'Guests';
		} else {
			$role = 'Users';
		}

		$this->miologger->log("ACL:: utente classificato come ".$role);

		$controller = $dispatcher->getControllerName();
		$action = $dispatcher->getActionName();

		/* Debugbar start */
		$ns = $dispatcher->getNamespaceName();
		if ($ns=='Snowair\Debugbar\Controllers') {
			return true;
		}
		/* Debugbar end */

		$acl = $this->getAcl();

		if (!$acl->isResource($controller)) {

			$this->miologger->log("ACL:: ".$controller."non sembra essere un controller! esco con 404");

			$dispatcher->forward([
				'controller' => 'errors',
				'action'     => 'show404'
			]);

			return false;
		}

		$allowed = $acl->isAllowed($role, $controller, $action);
		$accettazione = ($allowed ? "SI" : "NO");
		$this->miologger->log("ACL:: ruolo: {$role} controller: {$controller} action: {$action}. Lo accetto? {$accettazione}");

		if (!$allowed) {
			$this->miologger->log("ACL:: ruolo: {$role} controller: {$controller} action: {$action}. Lo accetto? {$accettazione}. Invece non lo acetto");
			$dispatcher->forward([
				'controller' => 'errors',
				'action'     => 'show401'
			]);
			$this->miologger->log("ACL:: distruggo la sessione e butto fuori tutti.");
            $this->session->destroy();
			return false;
		}
	}
}
