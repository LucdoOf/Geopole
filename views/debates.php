<?php
$title = "Débats";
$canonical = "/debates";
$description = "Nous proposons des débats dans lesquels vous pouvez réagir et donner votre avis avec les autres membres. Vous pouvez également créer votre propre débat et voir la communauté y répondre.";
$scripts = []; ?>

<?php ob_start(); ?>

<section id="body15" class="base-wrapper padded">
    <h1 class="section-title">Débats</h1>
    <p>
        Les débats sont des conversations spontanés créées par nos membres et approuvées par des modérateurs.
        Ils sont un moyen d'expression dans lequel chacun peut donner son avis et discuter avec d'autre membres.
        Vous aussi explorez nos débats et proposez en si l'envie vous en prend !
    </p>
    <div id="debates-ctn">
        <?php if(!empty($pinnedDebates) || !empty($debates)): ?>
            <?php if(!empty($pinnedDebates)): ?>
            <div class="table" id="pinned-debates">
                <?php foreach ($pinnedDebates as $debate){
                    DebateParser::parseList($debate);
                } ?>
            </div>
            <?php endif; ?>
            <?php if(!empty($debates)): ?>
            <div class="table" id="debates">
                <?php foreach ($debates as $debate){
                    DebateParser::parseList($debate);
                } ?>
            </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="message-wrapper" style="margin-top: 20px">Aucun débat n'est en ligne pour l'instant</div>
        <?php endif; ?>
    </div>
</section>

<?php $content = ob_get_clean(); ?>
<?php require(APPLICATION_PATH."/views/template.php"); ?>
