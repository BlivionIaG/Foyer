<h1>API</h1>

Api rest du projet foyer.

<h2>Routes</h2>

<h3>Produits</h3>
<ul>
  <li>GET <code>/product/</code> : récupération de tous les produits.</li>
  <li>GET <code>/product/id_product/{id_product}</code> : récupération d'un produit en fonction de son ID.</li>
  <li>GET <code>/product/available/{available}</code> : récupération de produits en fonction de leur état.</li>
  <li>POST <code>/product/</code> : ajout d'un nouveau produit.</li>
  <li>PUT <code>/product/{id_product}</code> : modification d'un produit.</li>
  <li>DELETE <code>/product/{id_product}</code> : suppression d'un produit.</li>
</ul>

<h3>Commandes</h3>
<ul>
  <li>GET <code>/command/</code> : récupération de toutes les commandes.</li>
  <li>GET <code>/command/id_product/{id_command}</code> : récupération d'une commande en fonction de son ID.</li>
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
</ul>

<h3>Autres</h3>
<ul>
  <li>GET <code>/date/</code> : récupération de la date du serveur.</li>
</ul>

<h2>Doc</h2>
Install apidoc <code>npm install apidoc -g</code>.
Génération de la doc <code>apidoc -i routes/ index.php -o doc/</code>.
Vous pourrez y accéder en allant à la racine de votre api.

<h2>Bugs</h2>
Si vous avez des erreur 404 cela vient surement du rewriting qui ne marche bien, attention à votre conf apache.