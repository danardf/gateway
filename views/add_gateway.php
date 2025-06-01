<div class="container-fluid pt-3">
    <p>
        <h1 class="title"><?= $title ?></h1>
    </p>
    <br>
    <div class="display full-border">
        <div class="row">
            <div class="col-md-12">
                <div class="fpbx-container">
                    <div class="display full-border">
                        <div class="row">
                            <div class="col-md-12">
                                <form class="fpbx-submit" method="post" action="?display=gateway">
                                    <div class="col-md-12 mybox">
                                        <h2 class="subtitle"><?= _("Configure your Extension as Gateway") ?>&nbsp;<i class="fa fa-caret-down"></i></h2>
                                        <br>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <?= _("Extension as Gateway") ?>&nbsp;<span class="fa fa-phone" aria-hidden="true" title="<?= _("Select an extension.") ?>"></span>
                                            </span>
                                            <select name="extension" id="extension" class="form-control">
                                                <?php 
                                                    foreach($users as $user){
                                                        echo "<option value='{$user["extension"]}'>{$user["extension"]} - {$user["name"]}</option>\n";
                                                    }
                                                ?>                                        
                                            </select>
                                        </div>
                                        <br>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <?= _("Contact") ?>&nbsp;<span class="fa fa-user" aria-hidden="true" title="<?= _("Contact") ?>"></span>
                                            </span>
                                            <input type="text" required id="contact" name="contact" class="form-control" aria-label="contact" placeholder="<?= _("Enter the name of the contact.") ?>">
                                        </div>
                                        <br>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <?= _("Description") ?>&nbsp;<span class="fa fa-file-text-o" aria-hidden="true" title="<?= _("Description") ?>"></span>
                                            </span>
                                            <input type="description" id="description" name="description" class="form-control" aria-label="description" placeholder="<?= _("Enter the description of the gateway.") ?>">
                                        </div>
                                        <br>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <?= _("Email") ?>&nbsp;<span class="fa fa-envelope-o" aria-hidden="true" title="<?= _("@Mail") ?>"></span>
                                            </span>
                                            <input type="email" id="email" name="email" class="form-control" aria-label="email" placeholder="<?= _("Enter the email of the contact.") ?>">
                                        </div>
                                        <br>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <?= _("Address") ?>&nbsp;<span class="fa fa-address-card" aria-hidden="true" title="<?= _("Address") ?>"></span>
                                            </span>
                                            <input type="text"  id="address" name="address" class="form-control" aria-label="name" placeholder="<?= _("Enter the address of the contact.") ?>">
                                        </div>
                                        <br>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <?= _("Zip code") ?>&nbsp;<span class="fa fa-address-card" aria-hidden="true" title="<?= _("Zip Code") ?>"></span>
                                            </span>
                                            <input type="text" id="zip" name="zip" class="form-control" aria-label="zip" placeholder="<?= _("Enter the ZIP code.") ?>">
                                        </div>
                                        <br>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <?= _("City") ?>&nbsp;<span class="fa fa-address-card" aria-hidden="true" title="<?= _("City") ?>"></span>
                                            </span>
                                            <input type="text" id="city" name="city" class="form-control" aria-label="city" placeholder="<?= _("Enter the city of the contact.") ?>">
                                        </div>
                                        <br>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <?= _("Country") ?>&nbsp;<span class="fa fa-address-card" aria-hidden="true" title="<?= _("Country") ?>"></span>
                                            </span>
                                            <input type="text" id="country" name="country" class="form-control" aria-label="country" placeholder="<?= _("Enter the country of the contact.") ?>">
                                        </div>
                                        <br>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <?= _("Gateway IP") ?>&nbsp;<span class="fa fa-server" aria-hidden="true" title="<?= _("Gateway IP address") ?>"></span>
                                            </span>
                                            <input type="text" required id="gateway" name="gateway" class="form-control" aria-label="gateway" placeholder="<?= _("Ip address of the remote gateway. (Public IP address)") ?>">
                                        </div>
                                        <br>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <?= _("DID Base") ?>&nbsp;<span class="fa fa-phone" aria-hidden="true" title="<?= _("Primary DID") ?>"></span>
                                            </span>
                                            <input type="tel" required id="primarydid" name="dids[]" class="form-control" aria-label="primarydid" placeholder="<?= _("Primary DID number to afect on this gateway (Usualy the same as your extension number).") ?>">
                                        </div>
                                        <h2 class="subtitle"><?= _("Additional DIDs attached to your gateway") ?> <i class="fa fa-caret-down"></i></h2>
                                        <br>
                                        <div class="addDID">
                                            <button class="btn brn-primary" id="addDID"><?= _("DID") ?>&nbsp;<i class="fa fa-plus"></i></button>
                                            <br>
                                        </div>                                        
                                        <div id="dids">
                                        </div>
                                    </div>
                                    <input type="hidden" name="edit" value="no">
                                </form>
                            </div>
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
            input += i18n.gettext('DID')+'&nbsp;&nbsp;<i class="fa fa-trash" id="delDid" title="'+i18n.gettext('Delete it')+'"></i></span>';
            input +='</span>';
            input +='<input type="tel" required name="dids[]" class="form-control" aria-label="dids" placeholder="'+i18n.gettext("DID number to add on this gateway (only one entry).")+'">';
            input +='</div>';
            $('#dids').append(input);
        })

    })
</script>