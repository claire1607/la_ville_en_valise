
$(document).ready(function(){

var $carrousel = $('#carrousel'), // on cible le bloc du carrousel
$img = $('#carrousel img'), // on cible les images contenues dans le carrousel
indexImg = $img.length - 1, // on définit l'index du dernier élément
i = 0, // on initialise un compteur
$currentImg = $img.eq(i); // enfin, on cible l'image courante, qui possède l'index i (0 pour l'instant)

$img.css('display', 'none'); // on cache les images
$currentImg.css('display', 'block'); // on affiche seulement l'image courante

$carrousel.append('<div class="controls"> <span class="prev">Precedent</span> <span class="next">Suivant</span> </div>');

$('.next').click(function(){ // image suivante

i++; // on incrémente le compteur

if( i <= indexImg ){ $img.css('display', 'none' ); // on cache les images $currentImg=$img.eq(i); // on définit la nouvelle image $currentImg.css('display', 'block' ); // puis on l'affiche } else{ i=indexImg; } }); $('.prev').click(function(){ // image précédente i--; // on décrémente le compteur, puis on réalise la même chose que pour la fonction "suivante" if( i>= 0 ){
    $img.css('display', 'none');
    $currentImg = $img.eq(i);
    $currentImg.css('display', 'block');
    }
    else{
    i = 0;
    }

    });

    function slideImg(){
    setTimeout(function(){ // on utilise une fonction anonyme

    if(i < indexImg){ // si le compteur est inférieur au dernier index i++; // on l'incrémente } else{ // sinon, on le remet à 0 (première image) i=0; } $img.css('display', 'none' ); $currentImg=$img.eq(i); $currentImg.css('display', 'block' ); slideImg(); // on oublie pas de relancer la fonction à la fin }, 7000); // on définit l'intervalle à 7000 millisecondes (7s) } slideImg(); // enfin, on lance la fonction une première fois });
    }

    $('#myCarousel').carousel(data-interval , false );
    // when the carousel slides, load the ajax content 
    $('#myCarousel').on('slid', function (e) {

// get index of currently active item 
var idx = $('#myCarousel .item.active').index(); var url = $('.item.active').data('url'); 
// ajax load from data-url 
$('.item').html("wait..."); $('.item').load(url,function(result){ $('#myCarousel').carousel(idx); }); }); 
// load first slide 
$('[data-slide-number=0]').load($('[data-slide-number=0]').data('url'),
function(result){ $('#myCarousel').carousel(0); });
    })
}
})