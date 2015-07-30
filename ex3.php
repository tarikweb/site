<?php
/*
githuuuuuuuuuuuuuub
Listing avec des requêtes Composées
<?php
/*
Listing avec des requêtes Composées

Dans ce code on utilise un requete avec jointure .

*/

$connexion = new PDO("mysql:dbname=base1;host=localhost;", "root" , "");// à adapter selon le nom de la base crée
$statement = $connexion->query("SELECT * FROM matiere ORDER BY id_matiere ASC;");
$matieres = $statement->fetchAll();

$tempon = array();
foreach ($matieres as $matiere){
  $tempon[$matiere["id_matiere"]] = array($matiere["sujet"] => null);
}
// Requete composée
$statement = $connexion->query("SELECT * FROM  eleve AS e 
                              JOIN note AS n ON n.id_eleve = e.id_eleve 
                              JOIN matiere AS m ON m.id_matiere = n.id_matiere ORDER BY m.id_matiere ASC;");
$results = $statement->fetchAll();


// Formatage d'un tableau pour l'affichage
$eleves = array();
foreach($results as $result){
    if(array_key_exists($result["id_eleve"] , $eleves)){
      $notes = $eleves[$result["id_eleve"]]["notes"];
      $notes[$result["id_matiere"]]  = array($result["sujet"] => $result["note"]) ;
      $eleves[$result["id_eleve"]]["notes"] = $notes;
    }else{
        $notes[$result["id_matiere"]] = array($result["sujet"] => $result["note"]); 
      $eleves[$result["id_eleve"]] = array("notes" => $notes , 
                                         "nom" => utf8_encode($result["nom"]) ,
                                         "prenom" => utf8_encode($result["prenom"]),
                                         );
    }
    unset($notes);
}

echo "<a href='./formulaire.php'>Ajouter une note</a>";
echo "<table>";
echo "<thead>
      <tr>
      <th>Nom</th>
      <th>Prenom</th>";
foreach($matieres as $matiere){
 echo "<th>".utf8_encode($matiere["sujet"])."</th>";
}
echo "</tr>
      </thead>";
echo "<tbody>";
foreach($eleves as $eleve){
  echo "<tr><td>".$eleve["nom"]." </td>
        <td>".$eleve["prenom"] ."</td>";
        $notes = $eleve["notes"];
  foreach($matieres  as  $matiere){
    if (isset($notes[$matiere["id_matiere"]])){
      $nts = $notes[$matiere["id_matiere"]];
      foreach($nts as $note){
        echo "<td>".$note."</td>";
      }
    }else {
            echo "<td></td>";
    }
    
  }
  echo "</tr>";
}
echo "</tbody>
      </table>";
