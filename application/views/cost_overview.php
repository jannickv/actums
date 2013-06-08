<?php 
$total = 0;
$moneyTotal = 0;
$amountTotal = 0;
$len = count($blocks);
?>
<div id="chronologie">
    <hr />
    &nbsp;&nbsp;Filter op start- en einddatum:&nbsp;&nbsp;
    <label>startdatum: </label>
    <input id="coststart" class="datepicker" type="text" value="" name="enddate">&nbsp;&nbsp;
    <label>einddatum: </label>
    <input id="costend" class="datepicker" type="text" value="" name="enddate">&nbsp;&nbsp;
    <input id="costfilter" type="submit" value="Filter" name="">&nbsp;&nbsp;
    <input id="costall" type="submit" value="Toon Alles" name="">
    <hr />
    
    <table id="costTable">
        <thead>
            <th>Datum</th>
            <th>Adres van</th>
            <th>Adres naar</th>
            <th>Activiteit</th>
            <th>Soort kost</th>
            <th>Bedrag (€)</th>
            <th>Aantal (Km)</th>
            <th>€/km</th>
            <th>Opmerking</th>
        </thead>
        <tbody id="costTableBody">
    <?php foreach($blocks as $block):?>
    
    <?php
        /*
         * omdat niet altijd de checkbox 'verslag' in de block onkost is aangevinbkt door de gebruiker
         * is het dus mogelijk dat de positie in de databse tabel 'blockdata' voor de blockfield 'onkosten'
         * ofwel op positie 17 of 18 staat, afhankelijk van het al dan niet aanwezig zijn van blockfield verslag
         * dit is één van de gevolgen van de te veel te statische opbouw van de code en de veel te
         * statische (of ironisch genoeg te dynamische) opbouw van de DB
         *
         * de nummering hier is de nummering vanuit de table blockfields:
         * als men daar gaat kijken voor alle rerods met blocktype = 13
         * dan moet vanaf het eerste record met dit blocktype beginnen tellen (vanaf 0 want het betreft hier een array)
         * op positie 16 in de DB (dus 15 in de array) staat het bedrag of het aantal => $block["data"][15]
         * op positie 15 in de DB (dus 14 in de array) staat het soort transport (kilometer, trien of andere) => $block["data"][14]
         * met deze twee berekenen we de totale onkosten (trein en andere enkel bedrag bijtellen
         * terwijl voor kilomters een km vergoeding moet vermenigvuldgd worden)
         * daarvoor gebruiken we 'value' kolom uit de tabel blockdata (waarin de blockfield_id als FK zit)
         *
         * voor de overige posities kan gewoon op dezelfde manier gerekend worden
         *
         * op positie 16 of 17 (17 of 18 in array) staat resp verslag en onkosten (als label in blockdata)
         *                                         of onkosten en chronologie (als geen verslag aangevinkt)
         *                                         of onkosten en niets (als geen verslag & chronologie aangevinkt)
         * als onkosten niet is aangevinkt dan zal er op geen enkele positie 'onkosten' staan en dus
         * vroeg de gebruiker door niet het niet aanvinken om deze kost niet mee op te nemen in het overzicht
         *
         * opmerking: het moge duidelijk zijn dat deze manier van werken (inDB en code) heel onhandig is)
         *            een betere database is dus vereist (alle block eigen tabel en GEEN input fieldtypes in de DB.
         */
    if((isset($block["data"][17]) && $block["data"][17]["label"] == "Onkosten") || (isset($block["data"][18]) && $block["data"][18]["label"] == "Onkosten")):
        //bereken het totaal
        //voor trein of andere: enkel bedrag nemen
        //voor kilometers, vermenigvuldig met juiste bedrag
        $currentKmCompensation = 0.69;
        $currentCostKind = $block["data"][14]["value"];
        $currentCostValue = $block["data"][15]["value"];
        switch($currentCostKind){
            case 'Kilometer':   //voor kilometers
                $total += ($currentCostValue * $currentKmCompensation);
                $amountTotal += ($currentCostValue * $currentKmCompensation);
                break;
            default:    //voor trein en andere
                $total += $currentCostValue;
                $moneyTotal += $currentCostValue;
                break;
        }

    ?>
            <tr class="costRow">
                <td class="costDate"><?php echo date("d-m-Y", strtotime($block["startdate"])) ?></td>
                <td><?php echo $block["data"][7]["value"]." ".$block["data"][8]["value"] ?></td>
                <td><?php echo $block["data"][11]["value"]." ".$block["data"][12]["value"] ?></td>
                <td><?php echo $block["data"][3]["value"]." - ".$block["data"][16]["value"] ?></td>
                <td><?php echo $block["data"][14]["value"] ?></td>
                <?php
                    if( $currentCostKind != 'Kilometer'  ){
                        echo "<td class='costValueMoney'>"
                            .$currentCostValue
                            ."</td><td></td>";
                    }else{
                        echo "<td></td><td class='costValueAmount'>"
                            .$currentCostValue
                            ."</td>";
                    }
                ?>
                <td><?php
                    echo ((( $currentCostKind == 'Kilometer'  ) && ($currentCostValue != '')) ? $currentKmCompensation : "")
                ?></td>
                <td><?php echo $block["comment"] ?></td>
            </tr>
    <?php endif; ?>
    <?php endforeach;?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td id="costTotalMoney">SubTot.: <strong><?php echo number_format($moneyTotal, 2, '.', ' ') ?></strong>€</td>
                <td colspan="2" id="costTotalAmount">SubTot.: <strong><?php  echo number_format($amountTotal, 2, '.', ' ') ?></strong>€</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="3" id="costTotal">Totaal: <strong><?php echo number_format($total, 2, '.', ' ') ?></strong>€</td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <?php if($len < 1): ?>
    
    Er is geen data om weer te geven. Rechts vind u een knop om data toe te voegen.
    
    <?php endif; ?>
    <div id="costPrint">
        <a href="#">Print Overzicht</a>
    </div>
</div>