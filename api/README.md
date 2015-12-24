<h1>API</h1>

Api rest du projet foyer.

<a target="_blank" href="http://digital-design.github.io/Foyer/">Lien vers la doc</a>

<h2>Installation</h2>
Faire la commande : <code>composer update</code>.
Changer les identifiants de la base de donnée dans <code>config/config.php</code>. Vous pouvez changer aussi le dossier des fichiers, ce dossier doit contenir le/les dossier(s) suivant(s) : product.
Après avoir choisi un dossier pour les fichiers ne pas oublier de lui donner les bons droits en utilisant le commande <code>sudo chown -R www-data:www-data mondossier</code>.

<h2>Routes</h2>

<h3>Produits</h3>
<ul>
  <li>GET <code>/product/</code> : récupération de tous les produits.</li>
  <li>GET <code>/product/id_product/{id_product}</code> : récupération d'un produit en fonction de son ID.</li>
  <li>GET <code>/product/available/{available}</code> : récupération de produits en fonction de leur état.</li>
  <li>POST <code>/product/</code> : ajout d'un nouveau produit.</li>
  <li>POST <code>/product/img/{id_product}</code> : ajout/modification d'une image d'un produit.</li>
  <li>PUT <code>/product/{id_product}</code> : modification d'un produit.</li>
  <li>DELETE <code>/product/{id_product}</code> : suppression d'un produit.</li>
</ul>

<h3>Commandes</h3>
<ul>
  <li>GET <code>/command/</code> : récupération de toutes les commandes.</li>
  <li>GET <code>/command/stats/</code> : récupération des stats de toutes les commandes.</li>
  <li>GET <code>/command/id_product/{id_command}</code> : récupération d'une commande en fonction de son ID.</li>
  <li>GET <code>/command/state/{state}</code> : récupération de commandes en fonction de son état.</li>
  <li>POST <code>/command/</code> : ajout d'une nouvelle commande.</li>
  <li>PUT <code>/command/{id_command}</code> : modification d'une commande.</li>
  <li>DELETE <code>/command/{id_command}</code> : suppression d'une commande.</li>
</ul>

<h3>Notifications</h3>
<ul>
  <li>GET <code>/notification/</code> : récupération de toutes les notifications.</li>
  <li>GET <code>/notification/id_notification/{id_notification}</code> : récupération d'une notification en fonction de son ID.</li>
  <li>GET <code>/notification/login/{login}</code> : récupération de notification en fonction du login (broadcast incluses).</li>
  <li>POST <code>/notification/</code> : ajout d'une nouvelle notification.</li>
  <li>PUT <code>/notification/{id_notification}</code> : modification d'une notification.</li>
  <li>DELETE <code>/notification/{id_notification}</code> : suppression d'une notification.</li>
  <li>DELETE <code>/notification/method/{method}</code> : suppression de notifications en fonction de leur méthode.</li>
</ul>

<h3>Users</h3>
<ul>
  <li>GET <code>/user/</code> : récupération de tous les users.</li>
  <li>GET <code>/user/login/{login}</code> : récupération d'un user en fonction de son login.</li>
  <li>POST <code>/user/</code> : ajout d'un nouveau user.</li>
  <li>PUT <code>/user/{login}</code> : modification d'un user.</li>
  <li>DELETE <code>/user/{login}</code> : suppression d'un user.</li>
</ul>

<h3>Autres</h3>
<ul>
  <li>GET <code>/date/</code> : récupération de la date du serveur.</li>
  <li>GET <code>/login/</code> : check la connexion pour l'app admin.</li>
  <li>POST <code>/login/</code> : connexion pour l'app admin.</li>
  <li>GET <code>/logout/</code> : deconnexion pour l'app admin.</li>
</ul>

<h2>Doc</h2>
Installer apidoc <code>npm install apidoc -g</code>.
Générer la doc avec la commande dans le dossier api <code>apidoc -i routes/ -o doc/</code>.
Vous pourrez y accéder en allant à la racine de votre api avec votre navigateur.

<h2>Bugs</h2>
Si vous avez des erreurs 404 sur vos requètes cela vient sûrement d'un problème de rewriting dans votre conf apache.
