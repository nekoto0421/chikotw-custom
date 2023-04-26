jQuery(function($) {
    $( document ).ready(function() {
        $('input[name="quantity"].hp-field.hp-field--number').closest(".hp-form__field").hide();

        $('input[name="_quantity"].hp-field.hp-field--number').closest(".hp-form__field").hide();

        $('input[name="booking_window"].hp-field.hp-field--number').closest(".hp-form__field").hide();
        $('input[name="booking_offset"].hp-field.hp-field--number').closest(".hp-form__field").hide();
    })
})