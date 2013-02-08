<?php
/**
 * Created by Florian Greinus
 */
$_MECHANICS = array(
    "MECHANIC_CHARM" => 0,
    "MECHANIC_DISORIENTED" => 1,
    "MECHANIC_DISARM" => 2,
    "MECHANIC_DISTRACT" => 3,
    "MECHANIC_FEAR" => 4,
    "MECHANIC_FUMBLE" => 5,
    "MECHANIC_ROOT" => 6,
    "MECHANIC_PACIFY" => 7,
    "MECHANIC_SILENCE" => 8,
    "MECHANIC_SLEEP" => 9,
    "MECHANIC_SNARE" => 10,
    "MECHANIC_STUN" => 11,
    "MECHANIC_FREEZE" => 12,
    "MECHANIC_KNOCKOUT" => 13,
    "MECHANIC_BLEED" => 14,
    "MECHANIC_BANDAGE" => 15,
    "MECHANIC_POLYMORPH" => 16,
    "MECHANIC_BANISH" => 17,
    "MECHANIC_SHIELD" => 18,
    "MECHANIC_SHACKLE" => 19,
    "MECHANIC_MOUNT" => 20,
    "MECHANIC_PERSUADE" => 21,
    "MECHANIC_TURN" => 22,
    "MECHANIC_HORROR" => 23,
    "MECHANIC_INVULNERABILITY" => 24,
    "MECHANIC_INTERRUPT" => 25,
    "MECHANIC_DAZE" => 26,
    "MECHANIC_DISCOVERY" => 27,
    "MECHANIC_IMMUNE_SHIELD" => 28,
    "MECHANIC_SAPPED" => 29
);
$baseUrl = "http://".$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];
$baseUrl = explode("?", $baseUrl);
$baseUrl = $baseUrl[0];
?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script type="text/javascript">
    var baseUrl = "<?php echo $baseUrl; ?>";

    function createUrl(mask)
    {
        var url = baseUrl + "?mask=" + mask;
        $("#directlink").val(url);
    }
    function calculateMask()
    {
        var newMask=0;
        $("input:checked").each(function(){
            newMask += parseInt($(this).val());
        });
        $("#ergebnis").val(newMask);
        createUrl(newMask);
    }
    function setCheckboxesFromMask(mask)
    {
        var immuneMask = parseInt(mask);
        $($("input[type=checkbox]").get().reverse()).each(function() {
           if ((immuneMask - parseInt($(this).val())>=0))
           {
               immuneMask -= parseInt($(this).val());
               $(this).prop("checked", true);
           }
           else
           {
               $(this).prop("checked", false);
           }
        });
        createUrl(mask);
    }
    $(document).ready(function(){
       $("input[type=checkbox]").change(function() {
           calculateMask();
       });
        $("#submitMask").click(function() {
            setCheckboxesFromMask(parseInt($("#ergebnis").val()));
        });
        $("#directlink").click(function() {
            $(this).select();
        });
		$("#clearForm").click(function() {
			$("input[type=checkbox]").each(function() {
				$(this).prop("checked", false);
			});
			calculateMask();
		});
		$("#checkAll").click(function() {
			$("input[type=checkbox]").each(function() {
				$(this).prop("checked", true);
			});
			calculateMask();
		});
        calculateMask();
    });
</script>
<style type="text/css">
    * { font-size:10pt; }
    td { background-color:white; }
    tr.dark td { background-color:#ccc; }
</style>
<table>
    <?php
        $checkedList = array();
        if (isset($_GET["mask"]))
        {
            $mask = $_GET["mask"];
            $temp = array_reverse($_MECHANICS);
            foreach($temp as $mechanic => $value)
            {
                $flag = pow(2, $value);
                if (($mask - $flag) >= 0)
                {
                    $mask -= $flag;
                    $checkedList[$mechanic] = "set";
                }
                else
                {
                    $checkedList[$mechanic] = "not set";
                }

            }
            if ( $mask > 0)
                echo "Fehlerhafte Maske";
        }
        foreach($_MECHANICS as $mechanic => $value)
        {
            if ($value % 2 != 0)
                echo "<tr><td>";
            else
                echo "<tr class='dark'><td>";
            if ((count($checkedList) > 0) && $checkedList[$mechanic] == "set")
            {
                echo "<label for='".$value."'>".$mechanic."</label></td><td><input id='".$value."' type='checkbox' name='".$value."' value='".(pow(2,$value))."' checked='true'>";
            }
            else
            {
                echo "<label for='".$value."'>".$mechanic."</label></td><td><input id='".$value."' type='checkbox' name='".$value."' value='".(pow(2,$value))."'>";
            }
            echo "</td></tr>";
        }
    ?>
    <tr><td><input type="text" value="0" id="ergebnis"></td><td><button id="submitMask">set</button></td></tr>
    <tr><td colspan=2><input type="text" value="" id="directlink" style="width:350px;"></td></tr>
	<tr><td><button id="clearForm">clear</button></td><td><button id="checkAll">mark all</button></td></tr>
</table>