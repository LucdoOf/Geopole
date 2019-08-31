<?php
/**
 * @var $sended bool|null
 */
$title = "Contact";
$canonical = "/contact";
$description = "Contactez-nous pour nous faire un retour personnel, pour nous rejoindre, si vous avez une suggestion ou pour quoi que ce soit d'autre.";
$mail = User::isConnected() ? User::getConnectedUser()->getEmail() : "";
$scripts = []; ?>

<?php ob_start(); ?>

<section id="body19" class="base-wrapper padded medium">
    <h1 class="section-title">Contactez nous</h1>
    <p id="paraph">Si vous souhaitez nous faire un retour personnel sur un article ou un débat,
        si vous avez repéré une anomalie, si vous souhaitez nous rejoindre, faire une suggestion ou pour quoi que ce soit
        d'autre, remplissez le formulaire ci-dessous et nous vous répondrons par mail dans les plus brefs
        délais
    </p>
    <form id="contact-form" method="POST" action="/contact/send">
        <label for="mail" class="field">
            Votre adresse email
            <input type="email" class="text-input" name="mail" placeholder="user@example.com" value="<?= $mail ?>">
        </label>
        <label for="reason" class="field">
            La raison de votre contact
            <div class="select-input">
                <select name="reason">
                    <option value="feedback">Faire un retour sur un article ou un débat</option>
                    <option value="suggestion">Suggestion</option>
                    <option value="abuse">Signaler un abus</option>
                    <option value="join">Nous rejoindre</option>
                    <option value="question">Une question</option>
                    <option value="bug">Vous avez détecté un bug</option>
                    <option value="other">Autre</option>
                </select>
            </div>
        </label>
        <label for="content" class="field" id="contact-content">
            Votre message
            <textarea class="textarea-input" name="content" placeholder="Donnez nous des détails à propos de votre message"></textarea>
        </label>
        <label class="field" for="submit" id="contact-submit">
            <input type="submit" name="submit" class="button cta" value="Envoyer">
        </label>
    </form>
</section>

<?php if(isset($sended) && !is_null($sended)){ ?>
    <script type="text/javascript">
        $(document).ready(() => {
            <?php if($sended == true): ?>
                PushTransmitter.pushSuccess("Votre message nous a été transmis ! Surveillez votre boite-mail nous allons y répondre");
            <?php else: ?>
                PushTransmitter.pushError("Une erreur est survenue lors de l'envoi du mail, verifiez que votre adresse mail est correcte et que vous nous avez bien précisé un message");
            <?php endif; ?>
        });
    </script>
<?php } ?>

<?php $content = ob_get_clean(); ?>
<?php require(APPLICATION_PATH."/views/template.php"); ?>

