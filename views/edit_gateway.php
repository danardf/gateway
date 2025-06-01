<div class="container-fluid">
    <p>
        <h1 class="title"><?= $title." - ".$gateway["extension"]?></h1>
    </p>
    <br>
    <div class="display full-border">
        <div class="row">
            <div class="col-sm-12">
                <div class="fpbx-container">
                    <div class="display full-border">
                        <div class="row">
                            <form class="fpbx-submit" method="post" action="?display=gateway">
                                <div class="col-md-12 mybox">
                                    <h2 class="subtitle"><?= _("Update your Gateway here") ?> <i class="fa fa-caret-down"></i></h2>
                                    <br>
                                    <input type="hidden" name="extension" id="extension" value="<?= $gateway["extension"] ?>">
                                    <br>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <?= _("Contact") ?>  <span class="fa fa-user" aria-hidden="true" title="<?= _("Contact") ?>"></span>
                                        </span>
                                        <input type="text" pattern="[a-zA-Z0-9éèçàùô_\s\-]+" required id="contact" name="contact" class="form-control" aria-label="contact" value="<?= $gateway["contact"] ?>" placeholder="<?= _("Enter the name of the contact.") ?>">
                                    </div>
                                    <br>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <?= _("Description") ?>  <span class="fa fa-file-text-o" aria-hidden="true" title="<?= _("Description") ?>"></span>
                                        </span>
                                        <input type="description" pattern="[a-zA-Z0-9éèçàùô_\s\-]+" name="description" class="form-control" aria-label="description" value="<?= $gateway["description"] ?>"  placeholder="<?= _("Enter the description of the gateway.") ?>">
                                    </div>
                                    <br>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <?= _("Email") ?>  <span class="fa fa-envelope-o" aria-hidden="true" title="<?= _("@Mail") ?>"></span>
                                        </span>
                                        <input type="email" id="email" name="email" class="form-control" aria-label="email" value="<?= $gateway["email"] ?>"  placeholder="<?= _("Enter the email of the contact.") ?>">
                                    </div>
                                    <br>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <?= _("Address") ?> <span class="fa fa-address-card" aria-hidden="true" title="<?= _("Address") ?>"></span>
                                        </span>
                                        <input type="text" pattern="[a-zA-Z0-9éèçàùô_\s\-]+" id="address" name="address" class="form-control" aria-label="name" value="<?= $gateway["address"] ?>" placeholder="<?= _("Enter the address") ?>">
                                    </div>
                                    <br>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <?= _("Zip code") ?> <span class="fa fa-address-card" aria-hidden="true" title="<?= _("Zip Code") ?>"></span>
                                        </span>
                                        <input type="text" pattern="\d{5}" id="zip" name="zip" class="form-control" aria-label="zip" value="<?= $gateway["zip_code"] ?>"  placeholder="<?= _("Enter the ZIP code") ?>">
                                    </div>
                                    <br>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <?= _("City") ?> <span class="fa fa-address-card" aria-hidden="true" title="<?= _("City") ?>"></span>
                                        </span>
                                        <input type="text" pattern="[a-zA-Z0-9éèçàùô_\s\-]+" id="city" name="city" class="form-control" aria-label="city" value="<?= $gateway["city"] ?>"  placeholder="<?= _("Enter the city") ?>">
                                    </div>
                                    <br>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <?= _("Country") ?> <span class="fa fa-address-card" aria-hidden="true" title="<?= _("Country") ?>"></span>
                                        </span>
                                        <input type="text" pattern="[a-zA-Zéèçàùô_\s\-]+" id="country" name="country" class="form-control" aria-label="country" value="<?= $gateway["country"] ?>"  placeholder="<?= _("Enter the country") ?>">
                                    </div>
                                    <br>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <?= _("Gateway IP") ?> <span class="fa fa-server" aria-hidden="true" title="<?= _("Gateway IP address") ?>"></span>
                                        </span>
                                        <input type="text" required id="gateway" name="gateway" class="form-control" aria-label="gateway" value="<?= $gateway["gateway"] ?>"  placeholder="<?= _("Ip address of the remote gateway. (Public IP address)") ?>">
                                    </div>
                                    <br>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <?= _("Primary DID") ?> <span class="fa fa-phone" aria-hidden="true" title="<?= _("Primary DID") ?>"></span>
                                        </span>
                                        <input pattern="[0-9]+" type="text" required id="" name="dids[]" class="form-control" aria-label="primarydid" placeholder="<?= _("Primary DID number to afect on this gateway (Usualy the same as your extension number).") ?>" value="<?= $gateway["extension"] ?>" readonly="readonly">
                                    </div>
                                    <h2 class="subtitle"><?= _("Additional DIDs attached to your gateway") ?> <i class="fa fa-caret-down"></i></h2>
                                    <br>
                                    <div class="addDID">
                                        <button class="btn brn-primary" id="addDID"><?= _("DID") ?> <i class="fa fa-plus"></i></button>
                                    </div>
                                    <div id="dids">
                                    <?php
                                        $gateways = json_decode($gateway["dids"], true);
                                        foreach($gateways as $index => $gtw){
                                            if($index != 0){
                                                $input  = '<div class="input-group" style="padding-top: 20px;">';
                                                $input .= '<span class="input-group-addon">';
                                                $input .= _('DID').' <span class="fa fa-phone" aria-hidden="true" title="'._('DID').'">&nbsp;&nbsp;<i class="fa fa-times-circle-o" id="delDid" title="'._('Delete it.').'"></i></span>';
                                                $input .='</span>';
                                                $input .='<input pattern="[0-9]+" type="text" required name="dids[]" class="form-control" aria-label="dids" value="'.$gtw.'" placeholder="'._("DID number to add on this gateway (only one entry).").'">';
                                                $input .='</div>';
                                                print($input);
                                            }
                                        }
                                    ?>
                                    </div>
                                </div>
                                <input type="hidden" name="edit" value="yes">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<p></p>

<script>
    $(document).ready(function(){
        var  i18n = new  Jed(
            <?=  $jsloc  ?>
        );

        updateDID();

        $("#extension").change( function(){
            updateDID();
        })

        $("#addDID").click( function(){
            var input = '<div class="input-group" style="padding-top: 20px;">';
            input += '<span class="input-group-addon">';
            input += i18n.gettext('DID')+' <span class="fa fa-phone" aria-hidden="true" title="'+i18n.gettext('DID')+'"> &nbsp;&nbsp;<i class="fa fa-trash" id="delDid" title="'+i18n.gettext('Delete it.')+'"></i></span>';
            input +='</span>';
            input +='<input type="tel" required name="dids[]" class="form-control" aria-label="dids" placeholder="'+i18n.gettext("DID number to add on this gateway (only one entry).")+'">';
            input +='</div>';
            $('#dids').append(input);
        })

    })
</script>