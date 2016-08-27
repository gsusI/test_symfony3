Symfony3
===========


##This is a test with Symfony3.

####It implements:
	- Etag with Listeners
	- Auth with Listeners and based on fixed tokens
	- Json responses
	- CRUD functionality
	- ManyToMany Bi Directional & ManyToOne doctrine2 relations
	- Some forms
 
####To install
   * Set ownership and permissions in var/cache, you can do chmod -R 777 var/cache
   * Set your database connection in app/config/parameters.yml
   * Run php bin/console doctrine:schema:update --force 
