<?php
/**
 * @var DebateOption[] $debateOptions
 * @var Debate $debate
 * @var DebateResponse[] $debateResponses
 * @var $page int
 * @var $counter int
 */
$pagePrev = $page > 0 ? "/debates/".$debate->getSlug()."?page=".($page-1) : null;
$pageNext = ceil($counter/DebatesController::RESPONSES_BY_PAGE)-1 > $page ? "/debates/".$debate->getSlug()."?page=".($page+1) : null;
$title = $debate->getTitle();
$description = Str::cutStr($debate->getDescription(), 255);
$canonical = "/debates/" . $debate->getSlug() . "?page=".$page;
$authorObj = new User($debate->getAuthor());
$author = $authorObj->getPseudo(); ?>

<?php ob_start(); ?>

<section id="body17" class="base-wrapper padded medium">
    <h1 class="section-title"><?= $debate->getTitle() ?></h1>
    <div id="debate-content">
        <img id="debate-image" src="<?= $debate->getImage() ?>" alt="<?= $debate->getSlug() ?>" onerror="this.onerror=null; this.src='/res/images/not-found.png'">
        <p id="debate-description"><?= $debate->getDescription() ?></p>
    </div>
    <div id="debate-options">
        <?php if(!empty($debateOptions)): ?>
            <?php foreach ($debateOptions as $debateOption): ?>
                <?php $index = $debateOption->getIndex() ?>
                <div class="debate-option index-<?= $index ?>">
                    <div class="name">
                        <?= $debateOption->getName() ?>
                    </div>
                    <div class="popularity">
                        <?= round($debateOption->getPopularity(),2) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="margin-top: -10px;">Ce débat est un débat ouvert, vous pouvez répondre sans sélectionner de réponse</p>
        <?php endif; ?>
    </div>
    <div id="debate-responses">
        <form action="/debates/<?= $debate->getSlug() ?>/answer" method="POST">
            <?php if(User::isConnected()): ?>
                <h3>Participez au débat</h3>
                <?php if(!empty($debateOptions)): ?>
                    <label>
                        Sélectionnez une option:
                        <div class="select-input">
                                <select name="option">
                                    <?php foreach ($debateOptions as $debateOption): ?>
                                        <option value="<?= $debateOption->getId() ?>"><?= $debateOption->getName() ?></option>
                                    <?php endforeach; ?>
                                </select>
                        </div>
                    </label>
                <?php endif; ?>
                <input type="text" name="title" class="text-input" placeholder="Entrez un titre pour votre réponse" id="response-title">
                <textarea class="textarea-input" id="response-content" name="content" placeholder="Argumentez ici"></textarea>
                <input type="submit" class="button cta">
            <?php else: ?>
                <p id="connect"><a href="/login" class="button cta">Connectez vous pour participer au débat</a></p>
            <?php endif; ?>
        </form>
        <div id="responses-ctn">
            <div class="responses">
                <?php
                    if(!empty($debateResponses)) {
                        foreach ($debateResponses as $response) {
                            $subResponses = $response->getSubResponses();
                            DebateParser::parseResponse($response, $subResponses);
                        }
                    } else {
                        ?>
                            <div id="no-responses">
                                Personne n'a encore répondu à ce débat, soyez le premier à le faire !
                            </div>
                        <?php
                    }
                ?>
            </div>
        </div>
        <section class="pager">
            <?= Pager::buildPager(intval($counter), DebatesController::RESPONSES_BY_PAGE, intval($page), function ($page) use ($debate) {
                return "/debates/".$debate->getSlug()."?page=".$page;
            }) ?>
        </section>
    </div>
</section>

<script type="text/javascript" src="/scripts/modules/CommentManager.js"></script>
<script type="text/javascript">
    var commentManager = new CommentManager("debate_response", "/debates/<?= $debate->getSlug() ?>", "/debates/<?= $debate->getSlug() ?>");
</script>

<?php $content = ob_get_clean(); ?>
<?php require(APPLICATION_PATH."/views/template.php"); ?>

