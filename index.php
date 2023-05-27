<?php
class Cours
{
    private $id;
    private $heure; // pour l'heure
    private $matieres; // un tableau pour les matières de la semaines

    // Méthode pour créer la base de données et la table si elles n'existent pas
    public static function creerBaseDeDonnees()
    {
        $pdo = new PDO('mysql:host=localhost', 'root', '');

        // Création de la base de données si elle n'existe pas
        $pdo->exec("CREATE DATABASE IF NOT EXISTS projet_ig3");

        // Sélection de la base de données
        $pdo->exec("USE projet_ig3");

        // Création de la table "cours" si elle n'existe pas
        $pdo->exec("CREATE TABLE IF NOT EXISTS cours (
            id INT AUTO_INCREMENT PRIMARY KEY,
            heure VARCHAR(100),
            courLundi VARCHAR(100),
            courMardi VARCHAR(100),
            courMercredi VARCHAR(100),
            courJeudi VARCHAR(100),
            courVendredi VARCHAR(100),
            courSamedi VARCHAR(100)
        )");
    }

    // Création des Setteurs (nous n'avons pas utiliser les Getteurs ici)
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setHeure($heure)
    {
        $this->heure = $heure;
    }

    public function setMatiere($matieres)
    {
        $this->matieres = $matieres;
    }

    // Méthode pour enregistrer une ligne, une ligne contient l'horaire et les matières de la semaine
    public function enregistrer()
    {
        self::creerBaseDeDonnees(); // Vérifier et créer la base de données si nécessaire

        $pdo = new PDO('mysql:host=localhost;dbname=projet_ig3', 'root', '');
        $stmt = $pdo->prepare('UPDATE cours SET heure = ?, courLundi = ?, courMardi = ?, courMercredi = ?, courJeudi = ?, courVendredi = ?, courSamedi = ? WHERE id = ?');

        $stmt->execute([
            $this->heure, $this->matieres[0], $this->matieres[1], $this->matieres[2],
            $this->matieres[3], $this->matieres[4], $this->matieres[5], $this->id
        ]);
    }

    // Méthode pour tout récupérer dans la BDD
    public function recupererCours()
    {
        self::creerBaseDeDonnees(); // Vérifier et créer la base de données si nécessaire

        $pdo = new PDO('mysql:host=localhost;dbname=projet_ig3', 'root', '');
        $stmt = $pdo->query('SELECT * FROM cours');
        $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $resultats;
    }

    // Méthode pour ajouter une nouvelle ligne (vide) en BDD
    public function ajouter()
    {
        self::creerBaseDeDonnees(); // Vérifier et créer la base de données si nécessaire

        $pdo = new PDO('mysql:host=localhost;dbname=projet_ig3', 'root', '');
        $stmt = $pdo->prepare('INSERT INTO cours (heure, courLundi, courMardi, courMercredi, courJeudi, courVendredi, courSamedi) 
                               VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            "", "", "", "", "", "", ""
        ]);
    }

    // On supprime entièrement une ligne, à savoir l'heure et tous les cours de la semaine
    public function supprimerLigne($id)
    {
        self::creerBaseDeDonnees(); // Vérifier et créer la base de données si nécessaire

        $pdo = new PDO('mysql:host=localhost;dbname=projet_ig3', 'root', '');
        $stmt = $pdo->prepare('DELETE FROM cours WHERE id = ?');
        $stmt->execute([$id]);
    }
}
?>

<!doctype html>
<html lang="fr">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Bootsrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <title>Gestion des emplois du temps</title>
    <style>
        /* Styles pour la transition du mode admin */
        .form-check-label {
            transition: color 0.3s, transform 0.3s;
        }

        /* Styles pour le mode admin actif */
        .form-check-input:checked~.form-check-label {
            color: yellow;
            /* Modifier la couleur souhaitée */
            transform: rotate(360deg);
            /* Modifier l'animation souhaitée */
        }

        input {
            box-shadow: none !important;
            outline: none !important;
        }
    </style>
</head>

<body style="background-image: url('paper.png');">

    <!-- Pour se connecter à la page d'administration de l'emploi du temps -->
    <header class="bg-dark fw-bold text-light p-3 mb-5">
        <div class="form-check form-switch m-2 d-flex justify-content-center cursor-pointer">
            <input class="form-check-input me-1" type="checkbox" id="toggleButton">
            <label class="form-check-label" for="toggleButton">basculer en mode admin</label>
        </div>
    </header>

    <!-- Affichage de l'emploi du temps -->
    <div class="container d-flex justify-content-center align-items-center table-responsive">
        <form method="post" action="index.php">
            <div class="d-flex justify-content-center">
                <div class="mb-5 d-none" id="ajouterLigne">
                    <button class="btn btn-primary me-2" title="ajouter une ligne et insérer des matières et horaire" name="ajouter">Ajouter ligne</button>
                    <?php
                    if (isset($_POST['ajouter'])) {
                        // Le bouton "ajouter" a été cliqué
                        $cours = new Cours(); // On crée une instance de Cours
                        $cours->ajouter(); // On appelle la méthode ajouter
                    }
                    ?>
                </div>
            </div>

            <table class="table">
                <tr>
                    <td class="p-0"><input readonly placeholder="" type="text" class="form-control bg-white fw-bold rounded-0 d-none"></td>
                    <td class="p-0"><input readonly value="Lundi" type="text" class="form-control bg-white fw-bold rounded-0"></td>
                    <td class="p-0"><input readonly value="Mardi" type="text" class="form-control bg-white fw-bold rounded-0"></td>
                    <td class="p-0"><input readonly value="Mercredi" type="text" class="form-control bg-white fw-bold rounded-0"></td>
                    <td class="p-0"><input readonly value="Jeudi" type="text" class="form-control bg-white fw-bold rounded-0"></td>
                    <td class="p-0"><input readonly value="Vendredi" type="text" class="form-control bg-white fw-bold rounded-0"></td>
                    <td class="p-0"><input readonly value="Samedi" type="text" class="form-control bg-white fw-bold rounded-0"></td>
                </tr>
                <tr id="exempleLigne" class="d-none">
                    <td class="p-0"><input readonly placeholder="ex: 8h - 9h" type="text" class="form-control rounded-0"></td>
                    <td class="p-0"><input readonly placeholder="ex: Maths" type="text" class="form-control rounded-0"></td>
                    <td class="p-0"><input readonly placeholder="ex: Anglais" type="text" class="form-control rounded-0"></td>
                    <td class="p-0"><input readonly placeholder="ex: Physique" type="text" class="form-control rounded-0"></td>
                    <td class="p-0"><input readonly placeholder="ex: Français" type="text" class="form-control rounded-0"></td>
                    <td class="p-0"><input readonly placeholder="ex: Chimie" type="text" class="form-control rounded-0"></td>
                    <td class="p-0"><input readonly placeholder="ex: Dessin" type="text" class="form-control rounded-0"></td>
                </tr>
                <?php
                $cours = new Cours(); // On crée une instance de Cours
                $resultats = $cours->recupererCours(); // On appelle la méthode pour avoir tous les
                // enregistrements en BDD

                if (count($resultats) > 0) {
                    // A l'aide d'une boucle, on affiche tous ces enregistrements dans ce format
                    foreach ($resultats as $resultat) {
                        echo "<tr class='entrerValeur'>";
                        echo "<td class='p-0'><input readonly name='heure[]' value=\"" . $resultat['heure'] . "\" type='text' class='form-control bg-white rounded-0'></td>";
                        echo "<td class='p-0'><input readonly name='courDuLundi[]' value=\"" . $resultat['courLundi'] . "\" type='text' class='form-control bg-white rounded-0'></td>";
                        echo "<td class='p-0'><input readonly name='courDuMardi[]' value=\"" . $resultat['courMardi'] . "\" type='text' class='form-control bg-white rounded-0'></td>";
                        echo "<td class='p-0'><input readonly name='courDuMercredi[]' value=\"" . $resultat['courMercredi'] . "\" type='text' class='form-control bg-white rounded-0'></td>";
                        echo "<td class='p-0'><input readonly name='courDuJeudi[]' value=\"" . $resultat['courJeudi'] . "\" type='text' class='form-control bg-white rounded-0'></td>";
                        echo "<td class='p-0'><input readonly name='courDuVendredi[]' value=\"" . $resultat['courVendredi'] . "\" type='text' class='form-control bg-white rounded-0'></td>";
                        echo "<td class='p-0'><input readonly name='courDuSamedi[]' value=\"" . $resultat['courSamedi'] . "\" type='text' class='form-control bg-white rounded-0'></td>";
                        echo "<td class='p-0 d-none afficheAdmin'><button title='supprimer la ligne' class='btn btn-outline-danger' value='" . $resultat['id'] . "' name='supprimer'><i class='bi bi-x-lg'></i></button></td>";
                        echo "<td class='p-0 d-none afficheAdmin'><button title='enregistrer la ligne' class='btn btn-outline-success' value='" . $resultat['id'] . "' name='modifier'><i class='bi bi-save2'></i></button></td>";
                        echo "<input class='d-none' type='hidden' name='id[]' value='" . $resultat['id'] . "'>";
                        echo "</tr>";
                    }

                    // Pour supprimer une ligne
                    if (isset($_POST['supprimer'])) {
                        $id = $_POST['supprimer'];
                        // Appeler la méthode supprimerLigne en passant $id comme paramètre
                        $cours->supprimerLigne($id);
                    }

                    // Pour modifier la ligne
                    if (isset($_POST['modifier'])) {
                        $ids = $_POST['id'];
                        $heures = $_POST['heure'];
                        $courDuLundi = $_POST['courDuLundi'];
                        $courDuMardi = $_POST['courDuMardi'];
                        $courDuMercredi = $_POST['courDuMercredi'];
                        $courDuJeudi = $_POST['courDuJeudi'];
                        $courDuVendredi = $_POST['courDuVendredi'];
                        $courDuSamedi = $_POST['courDuSamedi'];

                        foreach ($ids as $key => $id) {
                            $cours = new Cours();
                            $cours->setId($id);
                            $cours->setHeure($heures[$key]);
                            $cours->setMatiere([$courDuLundi[$key], $courDuMardi[$key], $courDuMercredi[$key], $courDuJeudi[$key], $courDuVendredi[$key], $courDuSamedi[$key]]);

                            $cours->enregistrer();
                        }
                    }
                } else {
                    echo "<div class='alert alert-danger' role='alert'>Il n'y a aucun cours enregistré pour l'instant</div>";
                }
                ?>

            </table>
        </form>
    </div>
    <!-- Optional JavaScript; choose one of the two! -->
    <script src="app.js"></script>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>