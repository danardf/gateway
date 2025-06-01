<br><h1 class="title text-center"><?= _("Gateway") ?></h1>
<section class="projects-clean">
    <div class="container">
        
        <div class="intro">
            <p class="text-center"><?= _("Document to configure the gateway") ?></p>
            
        </div>
        <h3 style="padding-top: 20px;"><p class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> <?= _("Trunks and extensions must be configured with the PJSIP driver. This means chan_sip is not supported!") ?></p></h3>
        <div class="row projects">
            <div class="col-lg-4 col-sm-6 item"><img class="myImg img-responsive" onclick="image(event)" src="<?= $img ?>/assets/img/grid.png" />
                <h3 class="name"><?= _("Main page") ?></h3>
                <p class="description" style="text-align: left;">
                    <?= _("When you go to the gateway page, you've got a grid showing all gateways configured like this.") ?><br /><br />
                    <?= _("This grid shows some useful things regarding this gateway.") ?><br /><br />
                </p>
            </div>
            <div class="col-lg-4 col-sm-6 item"><img class="myImg img-responsive" onclick="image(event)" src="<?= $img ?>/assets/img/add_gateway.png" />
                <h3 class="name"><?= _("Add Gateway") ?></h3>
                <p class="description" style="text-align: left;"><?= _("To add a gateway, click on the + Add button above the grid.")?><br /><br />
                <?= _("A page is displayed, you just have to fill in the desired fields.") ?><br /><br />                    
                <?= _("The required fields are:") ?><br /><br />
                    - <strong><?= _("Extension as gateway") ?></strong><br />
                    - <strong><?= _("Gateway") ?></strong><br />
                    - <strong><?= _("Primary DID") ?></strong><br /><br />
                    <?= _("Start by selecting an extension to convert to a gateway.") ?><br /><br />
                    <?= _("Enter the IP address of the gateway that will be connected to the extension. Example: 200.25.46.30 or 200.25.46.30:5061") ?><br /><br />
                    <?= _("If you do not specify the port in the IP address of your gateway, the port will point to 5060.") ?><br /><br />
                    <?= _("When you choose an extension to convert to a gateway, the Primary DID fields will be filled in automatically. The first DID will therefore be the head phone number.") ?><br /><br />
                    <?= _("You may add any DID number associated to the gateway clicking on DID + button. If the field DID is added, fill it, otherwise, you can delete it clicking on the trash button.") ?><br /><br />
                    <?= _("Fill in the other fields to identify the gateway. Then click on the Submit button.") ?><br /><br />
                </p>
            </div>
            <div class="col-lg-4 col-sm-6 item"><img class="myImg img-responsive" onclick="image(event)" src="<?= $img ?>/assets/img/edit_gateway.png" />
                <h3 class="name"><?= _("Edit Gateway") ?></h3>
                <p class="description" style="text-align: left;">
                    <?= _("On the grid, click on the Pencil icon on the right of row. Same type of fields used above. You can change everything excepted the field: Extension as Gateway and Primary DID.") ?>
                </p>
            </div>
        </div>
        <div class="row projects">
            <div class="col-lg-4 col-sm-6 item"><img class="myImg img-responsive" onclick="image(event)" src="<?= $img ?>/assets/img/grid.png" />
                <h3 class="name"><?= _("Delete Gateway") ?></h3>
                <p class="description" style="text-align: left;"><?= _("You can delete a gateway clicking on the trash icon.") ?></p>
            </div>
            <div class="col-lg-8 col-sm-6 item">
                    <pre class="mermaid" style="color: white;">
                        flowchart TD
                        A[Trunk PJSIP] <--> B(Context:  \n from-trunk-gateway)
                        B<--> C{Calls}
                        C<--> D[Context: \n from-internal-gateway\nfa:fa-server\nGW 1]
                        C<--> E[Context: \n from-internal-gateway\nfa:fa-server\nGW 2]
                        C<--> F[Context: \n from-internal-gateway\nfa:fa-server\nPBX 1]
                        D<--> G[fa:fa-phone\nExt 1]
                        D<--> h[fa:fa-phone\nExt 2]
                        E<--> i[fa:fa-phone\nExt 1]
                        E<--> j[fa:fa-phone\nExt 2]
                        F<--> k[fa:fa-phone\nExt 1]
                        F<--> L[fa:fa-phone\nExt 2]
                    </pre>  
                    <h3 class="name"><?= _("Setting Up FreePBX or PBXact") ?></h3>
                    <p class="description" style="text-align: left;">
                        <?= _("Once extensions (PJSIP) are used as a gateway, their context will change to:") ?> <strong>from-internal-gateway.</strong><br>
                        <?= _("Regarding the trunk (PJSIP) used to receive calls to gateways, set the context manually to:") ?> <strong>from-trunk-gateway.</strong><br>
                        <?= _("Look the schema above.") ?><br><br>
                    </p>
            </div>
        </div>
    </div>
</section>

<!-- The Modal -->
<div id="ModalImage" class="modal">
    <!-- The Close Button -->
    <span class="closeimage" style="padding-top: 30px;">&times;</span>

    <!-- Modal Content (The Image) -->
    <img class="modal-content" id="img01">

    <!-- Modal Caption (Image Text) -->
    <div id="caption"></div>
</div>

<p><br><br><br></p>
<script type="module">
  import mermaid from 'https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.esm.min.mjs';
  mermaid.initialize({ startOnLoad: true });
</script>


