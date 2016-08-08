<?php
include("includes/top_includes.php");
if(!isset($_SESSION['id_client']))
    header("Location: creer_compte.php");
$conn = new DbConnector();
?>
<!doctype html>
<html>
    <head>
        <?php include("includes/head.php"); ?>
        <title><?php showLang('PAGE_TITLE_MY_ACCOUNT') ?> - <?php showLang('PAGE_TITLE_COMMON') ?></title>
    </head>
<body>
<div class="page-container container">
    <?php include("includes/header.php"); ?>
    <div class="container">
        <div class="row" id="menuTabs">
            <div class="col-md-12">
                <ul id="myTab" class="nav nav-tabs">           
                    <li class="active"><a href="#order_history" data-toggle="tab">Historique des commandes</a></li>               
                    <li><a href="#addresses" data-toggle="tab">Mes adresses</a></li>               
                    <li><a href="#personal_info" data-toggle="tab">Mes informations personnelles</a></li>               
                </ul>
                <div id="myTabContent" class="tab-content">
                    <div class="tab-pane fade in active" id="order_history">
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                $query = "SELECT * FROM commandes WHERE id_client=".$_SESSION['id_client']." AND id_statut>1 ORDER BY id DESC";
                                $result = $conn->query($query);
                                if($conn->getNumRows($result)==0):
                                    showLang('NO_ORDERS');
                                else: ?>
                                     <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th><?php showLang('ORDER_ID') ?></th>
                                                <th><?php showLang('DATE') ?></th>
                                                <th><?php showLang('TOTAL') ?></th>
                                                <th><?php showLang('PAYMENT') ?></th>
                                                <th><?php showLang('STATUS') ?></th>
                                                <th><?php showLang('DETAILS') ?></th>
                                                <th><?php showLang('REORDER') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody><?php
                                        $i=1;
                                        while($row = $conn->fetchArray($result))
                                        { ?>
                                            <tr <?php if($i%2==0): ?> class="td_even" <?php endif; ?>>
                                                <td><?php echo $row['id'] ?></td>
                                                <td><?php echo displayDate($row['date']) ?></td>
                                                <td><?php echo showPriceCurrency($row['total_ttc']) ?></td>
                                                <td><?php echo getPaymentMethod($row['id_moyen_paiement']) ?></td>
                                                <td><?php echo getStatus($row['id_statut']) ?></td>
                                                <td><a href="" class="details_commande" data-toggle="collapse" data-target="#order_<?php echo $row['id'] ?>"><?php showLang('DETAILS') ?></a></td>
                                                <td class="reorder"><a href="" class="meme_commande" name="<?php echo $row['id'] ?>"><i class="fa fa-repeat"></i></a></td>
                                            </tr>
                                            <tr class="commande_details_tr collapse out" id="order_<?php echo $row['id'] ?>">
                                                <td colspan="7">
                                                    <div>
                                                        <!--<input type="button" class="meme_commande" value="meme commande" name="<?php echo $row['id'] ?>" style="display:none" />-->
                                                        <table class="commande_details table table-striped table-hover">
                                                            <thead>
                                                                <th><?php showLang('PHOTO') ?></th>
                                                                <th><?php showLang('DISH') ?></th>
                                                                <th><?php showLang('QUANTITY') ?></th>
                                                                <th><?php showLang('UNIT_PRICE') ?></th>
                                                                <th><?php showLang('TOTAL_PRICE') ?></th>
                                                            </thead>
                                                            <tbody><?php
                                                                // Plats
                                                                $query = "SELECT LC.*, P.name, P.thumbnail1 
                                                                            FROM ligne_commande LC
                                                                       LEFT JOIN plats P ON (LC.id_plat=P.id)
                                                                           WHERE id_commande=".$row['id']."
                                                                             AND remise=0 
                                                                        ORDER BY id";
                                                                $result_details = $conn->query($query);
                                                                while($row_details = $conn->fetchArray($result_details))
                                                                { ?>
                                                                    <tr>
                                                                        <td><img src="<?php echo $row_details['thumbnail1'] ?>" /></td>
                                                                        <td><?php 
                                                                            echo $row_details['name'];
                                                                            if(!is_null($row_details['options'])): ?>
                                                                                <div class="mezze_options_history"><?php echo $row_details['options'] ?></div><?php
                                                                            endif; ?>
                                                                        </td>
                                                                        <td><?php echo $row_details['quantite'] ?></td>
                                                                        <td><?php echo showPriceCurrency($row_details['prix_ttc']) ?></td>
                                                                        <td><?php echo showPriceCurrency($row_details['total_ttc']) ?></td>
                                                                    </tr><?php
                                                                } 
                                                                
                                                                // Remises
                                                                $query_vouchers = "SELECT * FROM ligne_commande LC WHERE id_commande=".$row['id']." AND remise=1";
                                                                $result_vouchers = $conn->query($query_vouchers);
                                                                while($row_voucher = $conn->fetchArray($result_vouchers))
                                                                { ?>
                                                                    <tr>
                                                                        <td></td>
                                                                        <td><?php echo osql($row_voucher['description_remise']) ?></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td><?php echo showPriceCurrency($row_voucher['total_ttc']) ?></td>
                                                                    </tr><?php
                                                                } 
                                                                ?> 
                                                            </tbody>
                                                        </table>
                                                     </div>
                                                </td>
                                            </tr><?php
                                            $i++;
                                         } ?>
                                        </tbody>
                                      </table><?php
                                endif; ?>   
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade in" id="addresses">
                    </div>
                    <div class="tab-pane fade in" id="personal_info">
                    </div>
                </div>
            </div>  
        </div>
    </div>      
    <?php include("includes/footer.php"); ?>
</div>
<?php include("includes/foot.php"); ?>
<script>
$(function() {
    var action = '<?php echo isset($_GET['action']) ? $_GET['action'] : '' ?>';
    if(action=='ajouter_adresse')
    {
        $('#myTab li:eq(1) a').click();
    }
    
    $.post(
        "ajax_all_addresses.php", 
        {backlink : '<?php echo isset($_GET['back']) ? $_GET['back'] : '' ?>', step : '<?php echo isset($_GET['step']) ? $_GET['step'] : '' ?>'}, 
        function(data){
            $("#addresses").html(data);
            if(action=='ajouter_adresse')
            {
                $("#add_new_address").hide();
                $('.msgbox').remove();
                $(".address_info").hide();
                $(".address_form").hide();
                $(".address_form_empty").show();
            }
        }
    );
    $("#personal_info").load('ajax_personal_info.php');
});
</script>
</body>
</html>
