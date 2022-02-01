<nav class="p-3 navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Bibliothèque</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
    <div class="navbar-nav">
      <a class="nav-item nav-link" href="/library/public/">Accueil</a>
      <a class="nav-item nav-link" href="/library/public/user">Utilisateurs</a>
      
    </div>
  </div>
  <a href="{{ path('app_logout') }}" class='mx-3 btn btn-danger'>
        Se deconnecter
      </a>
</nav>