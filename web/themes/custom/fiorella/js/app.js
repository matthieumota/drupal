(($, Drupal, once) => {
    // DOM Chargé / Site classique
    $(document).ready(() => {
        $('article h2').click((event) => {
            event.preventDefault();

            console.log('SANS DRUPAL : ' + $(event.target).text().trim());
        });
    });

    // Changement d'état dans Drupal
    Drupal.behaviors.myFeature = {
        attach: function (context, settings) {
            once('myFeature', 'article h2', context).forEach(function (element) {
                $(element).click((e) => {
                    e.preventDefault();
        
                    console.log('AVEC DRUPAL : ' + $(element).text().trim());
                    console.log(settings);
                });
            });
        }
    }
})(jQuery, Drupal, once);
