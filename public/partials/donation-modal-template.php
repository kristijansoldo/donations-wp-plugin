<?php

/**
 * Donation modal template
 *
 * Available variables:
 *
 * @var     $donations                Donation[]
 *
 **/

?>

<!-- Trigger/Open The Modal -->
<button id="donateModalBtn" class="dp-donate-button">Donate</button>

<!-- The Modal -->
<div id="donateModal" class="dp-modal">

    <!-- Modal content -->
    <div class="dp-modal-content">
        <span class="dp-close">&times;</span>
        <h2><?php _e('Donate', DP_PLUGIN_TEXTOMAIN) ?></h2>
        <?php

        $id_suffix = 'modal';
        $template_path = dirname( __FILE__ ) . '/../../public/partials/donation-form-template.php';

        include($template_path);
        ?>
    </div>

</div>

<script>
    // Get the modal
    var modal = document.getElementById("donateModal");

    // Get the button that opens the modal
    var btn = document.getElementById("donateModalBtn");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("dp-close")[0];

    // When the user clicks the button, open the modal
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }</script>