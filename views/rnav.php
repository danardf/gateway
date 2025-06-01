<?php
$item = !empty($action) ? $action : "";
?>
<ul class="nav flex-column">
  <li class="nav-item" style="padding-top: 5px;">
    <a href="config.php?display=gateway&action=help" class="btn <?php echo $item === "help" ? "active" : ""; ?>" style="text-align: start;"><i class="fa fa-info-circle "></i>&nbsp;&nbsp;<?php echo _('Help')?></a>
  </li>
  <li class="nav-item" style="padding-top: 5px;">
    <a href="config.php?display=gateway" class="btn "<?php echo $item === ""? "active" : ""; ?> style="text-align: start;"><i class="fa fa-home"></i>&nbsp;&nbsp;<?php echo _('Home')?></a>
  </li>
</ul>
