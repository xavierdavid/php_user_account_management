<h1>Bonjour <?PHP echo ($user_data->getFirstName()." ".$user_data->getLastName()); ?></h1>

<section>
    <h2>Bienvenue sur votre page de profil !</h2>
    <p>Vos informations</p>
    <div id="email">
        <p>Email : <strong><?PHP echo ($user_data->getEmail());?></strong></p>
        <p>Votre rôle : <strong><?PHP echo ($user_data->getRole());?></strong></p>
        <p>Votre rôle avec récupéré avec la session : <strong><?PHP echo ($_SESSION['user']['role']);?></strong></p>
    </div>
</section>