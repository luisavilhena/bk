//current menu link bold
document.addEventListener('DOMContentLoaded', function () {
  if (window.PhotoSwipe) {
    var originalGetViewportSize = PhotoSwipe.prototype.getViewportSize;

    PhotoSwipe.prototype.getViewportSize = function() {
      var size = originalGetViewportSize.call(this);
      return {
        x: size.x * 0.9,  // largura máxima (90%)
        y: size.y * 0.85  // altura máxima (85%)
      };
    };
  }
});

console.log('aqui')
jQuery(document).ready(function($) {
    var url = window.location.href;
    $('.menu-item a').each(function() {
        if (url == (this.href)) {
            $(this).closest('.menu-item').addClass('current-menu-item');
        }
    });
});
document.querySelectorAll('nav a').forEach(link => {
	if (!link.dataset.text) {
	  link.dataset.text = link.textContent.trim();
	}
});

//ler mais single projetos
document.querySelectorAll('.toggle-descricao').forEach(button => {
	button.addEventListener('click', () => {
	  const container = button.closest('.descricao-do-projeto');
	  const isOpen = container.classList.toggle('is-open');
  
	  button.textContent = isOpen ? 'Ler menos' : 'Ler mais';
	});
});
  
  



//CAROUSEL
$(document).ready(function(){
	var myCarousel = $("#carousel-img");
	console.log(myCarousel);

  myCarousel.each(function() {
  	$(this).slick({
		  slidesToShow: 1,
		  slidesToScroll: 1,
		  autoplay: true,
		  speed: 2000,
		  autoplaySpeed: 2000,
		  dots: false,
		  adaptiveHeight: true,
  	});
  });
  	var myCarouselDescriptionItem = $(".carousel-description__item");
  	console.log(myCarousel);

    myCarouselDescriptionItem.each(function() {
    	$(this).slick({
			  slidesToShow: 1,
			  slidesToScroll: 1,
			  arrows: false,
			  fade: true,
			  asNavFor: '.carousel-mini__nav'
    	});
    	$('.carousel-mini__nav').slick({
    	  slidesToShow: 5,
    	  slidesToScroll: 1,
    	  asNavFor: '.carousel-description__item',
    	  dots: false,
    	  focusOnSelect: true
    	});

    });

    var myCarouselMini = $(".carousel-mini__item");

     $('.carousel-mini__item').slick({
			  slidesToShow: 1,
			  slidesToScroll: 1,
			  arrows: false,
			  fade: true,
			  asNavFor: '.carousel-mini__nav'
			});
			$('.carousel-mini__nav').slick({
			  slidesToShow: 5,
			  slidesToScroll: 1,
			  asNavFor: '.carousel-mini__item',
			  dots: false,
			  focusOnSelect: true
			});
});

$(document).ready(function(){
	$('#carousel-main-item').slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		autoplay: true,
		autoplaySpeed: 3000,
		speed: 2000,
		fade: true,
	});
	$('#carousel-arrow-item').slick({
		slidesToShow: 1,
		slidesToScroll: 1,
	  
		infinite: true,
	  
		autoplay: true,
		autoplaySpeed: 3000, // 👈 tempo de leitura
		speed: 600,          // 👈 só a animação
	  
		dots: true,
		arrows: true,
	  
		swipe: true,
		touchMove: true,
		draggable: true,
	  
		swipeToSlide: false,
	  
		pauseOnHover: false,
		pauseOnFocus: false,
	  });
	  
	  
		
	$('#carousel-project').slick({
		centerMode: true,
		slidesToShow: 1,
		slidesToScroll: 1,
		autoplay: true,
		autoplaySpeed: 2000,
		speed: 2000,
		dots: true,
	});
});
jQuery(function ($) {

	// ================================
	// VARIÁVEIS
	// ================================
	let imagesByIndex = {};
	let currentIndex = 0;
	let isDragging = false;
  
	const lightbox   = $('#carousel-lightbox');
	const lightboxImg = $('#carousel-lightbox img');
  
	// ================================
	// DETECTA SWIPE (MOBILE)
	// ================================
	$('#carousel-arrow-item')
	  .on('touchstart', function () {
		isDragging = false;
	  })
	  .on('touchmove', function () {
		isDragging = true;
	  });
  
	// ================================
	// COLETA IMAGENS REAIS (SEM CLONES)
	// ================================
	function collectImages() {
	  imagesByIndex = {};
  
	  $('#carousel-arrow-item .carousel-arrow-item__item:not(.slick-cloned)')
		.find('.carousel-lightbox-trigger')
		.each(function () {
  
		  const index = parseInt($(this).data('index'), 10);
		  const href  = $(this).attr('href');
  
		  if (!isNaN(index)) {
			imagesByIndex[index] = href;
		  }
		});
	}
  
	// ================================
	// ABRIR LIGHTBOX
	// ================================
	function openLightbox(index) {
	  if (!imagesByIndex[index]) return;
  
	  currentIndex = index;
  
	  lightboxImg.attr('src', imagesByIndex[currentIndex]);
	  lightbox
		.addClass('is-open')
		.attr('aria-hidden', 'false');
  
	  $('body').css('overflow', 'hidden');
	}
  
	// ================================
	// FECHAR LIGHTBOX
	// ================================
	function closeLightbox() {
	  lightbox
		.removeClass('is-open')
		.attr('aria-hidden', 'true');
  
	  lightboxImg.attr('src', '');
	  $('body').css('overflow', '');
	}
  
	// ================================
	// NAVEGAÇÃO
	// ================================
	function getIndexes() {
	  return Object.keys(imagesByIndex)
		.map(Number)
		.sort((a, b) => a - b);
	}
  
	function nextImage() {
	  const keys = getIndexes();
	  const pos  = keys.indexOf(currentIndex);
	  const next = keys[pos + 1] ?? keys[0];
	  openLightbox(next);
	}
  
	function prevImage() {
	  const keys = getIndexes();
	  const pos  = keys.indexOf(currentIndex);
	  const prev = keys[pos - 1] ?? keys[keys.length - 1];
	  openLightbox(prev);
	}
  
	// ================================
	// EVENTOS
	// ================================
  
	// TAP / CLICK NA IMAGEM
	$(document).on('click touchend', '.carousel-lightbox-trigger', function (e) {
  
	  // se foi swipe, não abre
	  if (isDragging) {
		isDragging = false;
		return;
	  }
  
	  e.preventDefault();
  
	  collectImages(); // sincroniza com DOM real
  
	  const index = parseInt($(this).data('index'), 10);
	  openLightbox(index);
	});
  
	// FECHAR
	$('.carousel-lightbox__overlay, .carousel-lightbox__close')
	  .on('click', closeLightbox);
  
	// NAVEGAÇÃO POR BOTÕES
	$('.carousel-lightbox__nav.next')
	  .on('click', function (e) {
		e.stopPropagation();
		nextImage();
	  });
  
	$('.carousel-lightbox__nav.prev')
	  .on('click', function (e) {
		e.stopPropagation();
		prevImage();
	  });
  
	// TECLADO
	$(document).on('keydown', function (e) {
	  if (!lightbox.hasClass('is-open')) return;
  
	  if (e.key === 'Escape')    closeLightbox();
	  if (e.key === 'ArrowRight') nextImage();
	  if (e.key === 'ArrowLeft')  prevImage();
	});
  
  });
  
  
  
  
  
jQuery(function ($) {

    $('.publicacoes-list').each(function () {
        const $list = $(this);

        if (!$list.hasClass('slick-initialized')) {
            $list.slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: false,
                speed: 2000,
                autoplaySpeed: 2000,
                dots: false,
				variableWidth: true,
				infinite: false,


            });
        }
    });

});
jQuery(function ($) {

    $('.archive-publicacoes').on('click', '.more', function (e) {
        e.preventDefault();

        const $group = $(this).closest('.publicacao-group');
        const $list  = $group.find('.publicacoes-list');
        const isOpen = $group.hasClass('expanded');

        // 🔥 FECHA TODOS OS OUTROS
        $('.publicacao-group.expanded').not($group).each(function () {
            const $g = $(this);
            const $l = $g.find('.publicacoes-list');

            $g.removeClass('expanded');

            if (!$l.hasClass('slick-initialized')) {
                $l.slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    autoplay: false,
                    speed: 2000,
                    autoplaySpeed: 2000,
                    dots: false,
                    variableWidth: true,
					infinite: false

                });
            }
        });

        if (isOpen) {
            // 🔒 FECHAR ATUAL
            $group.removeClass('expanded');

            if (!$list.hasClass('slick-initialized')) {
                $list.slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    autoplay: false,
                    speed: 2000,
                    autoplaySpeed: 2000,
                    dots: false,
					variableWidth: true,
					infinite: false
                });
            }

        } else {
            // 🔓 ABRIR ATUAL
            if ($list.hasClass('slick-initialized')) {
                $list.slick('unslick');
            }

            $group.addClass('expanded');
        }
    });

});









//TEXT BOX CLICk
$(document).ready(function(){
	window.addEventListener('locationchange', function(){
    console.log('location changed!', $('.text-box__item__more'));
    setTimeout(function(){
    	$('.text-box__item__more').on("click", function(e){
    		$(this).toggleClass("active")
    		console.log('clicou location')
    	})
    	$('.text-box__item h4').on("click", function(e){
    		$(this).toggleClass("active")
    	})  	
    }, 0)
	})
	$('.text-box__item h4').on("click", function(e){
		$(this).toggleClass("active")
	})
})

$(document).ready(function(){
	$('.text-box__item__more').on("click", function(e){
		console.log('clicou out location')
		$(this).toggleClass("active")
	})
	if($('.text-box__item__more a')){
		$('.text-box__item__more').on("click", function(e){x
			$(this).removeClass("active")
		})
	}

})


//ABOUT SCROLL
$(document).ready(function(){
	$(function() {
	  $('.arrow').click(function(e) {
	  	const heightElement = window.pageYOffset
	      $('html, body').animate({ scrollTop: $('html').offset().top  + heightElement + 600}, 1000);
	  });
	});
})
//MENU OPEN

$(document).ready(function(){
	$('#mobile-menu-trigger').on("click", function(e){
		$('#main-header').toggleClass('menu-open');
		$('#footer').toggleClass('footer-sticky');
	});
});


//ABOUT TAMANHO DO FIXED
$(document).ready(function(){
	$(function() {
		const w = $(window).height()
		const h = $('.about__content__fixed-right').height()
	  const height = w - h - 175
	  $('#part-3').css('margin-bottom', height)
	});
})


$(document).ready(function(){
	$('#calendar_booking1 .datepick-header span').text('oct');
	console.log(	$('.datepick-header span').text('oct'))
});



///////////////////CAROUSEL/////////////////////////////
$(document).ready(function(){
	if($('.carousel__imgs')){
		function initializeCarousel() {
			$('.carousel__imgs').slick({
				slidesToShow: 1,
				slidesToScroll: 1,
				autoplay: true,
				speed: 1000,
				autoplaySpeed: 3000, // Alterei para 3 segundos para uma transição mais suave
				dots: true,
				fade: true,
				waitForAnimate: false,
			});
	
			setTimeout(function() {
				var dots = $('.slick-dots li');
				dots.each(function(k, v){
				$(this).find('button').addClass('heading' + k);
				});
		
				var items = $('.carousel__imgs').slick('getSlick').$slides;
				items.each(function(k, v){
				var text = $(this).find('h2').text();
				$('.heading' + k).text(text);
				});
			}, 2000);
	
			$('.carousel__imgs__item__img').on('click', function(e){
				$('.carousel__imgs').slick('slickPause');
			});
		
			$('.slick-dots li button').on('click', function(e){
				$('.carousel__imgs').slick('slickPause');
				console.log("clicou em cima");
			});
			$('.slick-dots li button').mouseenter(function() {
				$(this).click();
			});
			$('.slick-dots li button').mouseleave(function() {
				$('.carousel__imgs').slick('slickPlay');
			});
		}
	
		function checkScreenWidth() {
		if ($(window).width() > 850) {
			initializeCarousel();
		} else {
			// Se a largura da tela for menor que 850px, destrua o carrossel se estiver inicializado
			if ($('.carousel__imgs').hasClass('slick-initialized')) {
			$('.carousel__imgs').slick('unslick');
			}
		}
		}
	}
  
	// Chamar a função ao carregar a página e redimensionar a tela
	checkScreenWidth();
	$(window).resize(checkScreenWidth);

	//back to top
	function scrollToTop() {
        $('html, body').animate({ scrollTop: 0 }, 'slow');
    }

    // Evento de clique no botão "Back to Top"
    $('.animationbacktotop').click(function(e) {
		e.preventDefault()
        scrollToTop();
    });
});
  
document.addEventListener("DOMContentLoaded", function() {
	// Verifica se há um elemento com ID 'cinza'
	if (document.getElementById("cinza")) {
		// Se existir, aplica o background cinza no cabeçalho e no rodapé
		document.getElementById("main-header").style.backgroundColor = "#e7e7e7";
		$("body").css("backgroundColor", "#e7e7e7" );
		document.getElementById("main-header").style.backgroundColor = "#e7e7e7";
		document.getElementById("main-menu-container").style.backgroundColor = "#e7e7e7";
		document.getElementById("footer").style.backgroundColor = "#e7e7e7";
	  }
  });
  
  


document.addEventListener('DOMContentLoaded', () => {

    const space  = document.querySelector('.space');
    const filter = document.querySelector('.filter');

    if (!space || !filter) return;

    space.addEventListener('click', () => {
        filter.classList.toggle('mobile-open');
    });

});

function getCenteredSlide(slick) {
    const center = slick.$list.width() / 2;
    let closest = null;
    let minDist = Infinity;

    slick.$slides.each(function () {
        const $slide = $(this);
        const left   = $slide.position().left;
        const width  = $slide.outerWidth();
        const slideCenter = left + width / 2;

        const dist = Math.abs(slideCenter - center);

        if (dist < minDist) {
            minDist = dist;
            closest = $slide;
        }
    });

    return closest;
}
jQuery(function ($) {

    const $timeline = $('#timeline');
    if (!$timeline.length) return;

    $timeline.slick({
		variableWidth: true,
		slidesToScroll: 1,
		infinite: false,
		arrows: false,
		dots: false,
	
		draggable: true,
		swipe: true,
		touchMove: true,
	
		// 🔥 ESSENCIAIS para suavidade
		cssEase: 'linear',
		speed: 800,
		waitForAnimate: false,
		touchThreshold: 15,     // 🔥 reduz "pulos"
		swipeToSlide: true,     // 🔥 MUITO IMPORTANTE
		centerMode: false       // 🔴 centerMode deixa o drag ruim
    });

});


jQuery(function ($) {

	const $timeline = $('#timeline');
	const $years    = $('.timeline-year');
	const $track    = $('.timeline-track');
	const $scrubber = $('.timeline-scrubber');
	const $handle   = $('.timeline-handle');
	
	if (!$timeline.length || !$years.length) return;
	
	/* =====================================================
	   MAPA FIXO: ANO → SLIDE → ÍNDICE VISUAL
	===================================================== */
	const yearMap = [];
	
	$years.each(function (index) {
		yearMap.push({
			year:  String($(this).data('year')),
			slide: Number($(this).data('slide')),
			index,
			el: this
		});
	});
	
	/* =====================================================
	   MOVE SCRUBBER PARA UM ANO (visual)
	===================================================== */
	function moveHandleToYearIndex(index, animate = true) {
		const $year = $years.eq(index);
		if (!$year.length) return;
	
		const trackLeft = $track.offset().left;
		const yearLeft  = $year.offset().left;
		const yearW     = $year.outerWidth();
		const handleW   = $handle.outerWidth();
	
		const x = yearLeft - trackLeft + (yearW / 2) - (handleW / 2);
	
		if (animate) {
			$handle.stop().animate({ left: x }, 300);
		} else {
			$handle.css('left', x);
		}
	}
	
	
	
	/* =====================================================
	   CLICK NO ANO → VAI PARA O SLIDE
	===================================================== */
	$years.on('click', function () {
		const index = $years.index(this);
		const slide = Number(this.dataset.slide);
	
		if (!Number.isInteger(slide)) return;
	
		$timeline.slick('slickGoTo', slide);
	
		$years.removeClass('active');
		$(this).addClass('active');
	
		moveHandleToYearIndex(index); // ✅ correto
	});
	
	
	/* =====================================================
	   SLICK → ATUALIZA ANO + SCRUBBER
	===================================================== */
	$timeline.on('afterChange', function (e, slick, currentSlide) {

		const $slide = $(slick.$slides[currentSlide]);
		const $project = $slide.find('.project-card');
	
		if (!$project.length) return;
	
		const year = String($project.data('year'));
	
		const entry = yearMap.find(item => item.year === year);
		if (!entry) return;
	
		$years.removeClass('active');
		$(entry.el).addClass('active');
	
		moveHandleToYearIndex(entry.index);
	});
	
	
	/* =====================================================
	   SCROLL DO MOUSE → NAVEGA SLIDES
	===================================================== */
	let wheelTimeout = null;

	$timeline.on('wheel', function (e) {
		e.preventDefault();
	
		clearTimeout(wheelTimeout);
	
		wheelTimeout = setTimeout(() => {
			if (e.originalEvent.deltaY > 0) {
				$timeline.slick('slickNext');
			} else {
				$timeline.slick('slickPrev');
			}
		}, 20); // quanto maior, mais suave
	});
	
	
	/* =====================================================
	   SCRUBBER — DRAG
	===================================================== */
	let dragging = false;
	
	$handle.on('mousedown', function () {
		dragging = true;
		$handle.addClass('dragging');
	});
	
	$(document).on('mouseup', function () {
		if (!dragging) return;
		dragging = false;
		$handle.removeClass('dragging');
		snapToClosestYear();
	});
	
	$(document).on('mousemove', function (e) {
		if (!dragging) return;
	
		const rect = $track[0].getBoundingClientRect();
		const handleW = $handle.outerWidth();
	
		let x = e.clientX - rect.left - handleW / 2;
		x = Math.max(0, Math.min(x, rect.width - handleW));
	
		$handle.css('left', x + 'px');
	});
	
	/* =====================================================
	   SNAP → ANO MAIS PRÓXIMO
	===================================================== */
	function snapToClosestYear() {
		const handleLeft = parseFloat($handle.css('left')) || 0;
		let closest = null;
		let minDist = Infinity;
	
		$years.each(function (index) {
			const yearCenter =
				$(this).offset().left -
				$track.offset().left +
				($(this).outerWidth() / 2);
	
			const handleCenter = handleLeft + ($handle.outerWidth() / 2);
			const dist = Math.abs(handleCenter - yearCenter);
	
			if (dist < minDist) {
				minDist = dist;
				closest = yearMap[index];
			}
		});
	
		if (!closest) return;
	
		$timeline.slick('slickGoTo', closest.slide);
		moveHandleToYearIndex(closest.index);
	
		$years.removeClass('active');
		$(closest.el).addClass('active');
	}
	
	/* =====================================================
	   ESTADO INICIAL
	===================================================== */
	moveHandleToYearIndex(0);
	$years.first().addClass('active');
	
	});


	jQuery(function ($) {

		$('.projects-carousel').on('click', '.project-card .text', function (e) {
			e.stopPropagation();
	
			const $card = $(this).closest('.project-card');
	
			// 🔥 remove de todos os outros
			$('.project-card.horizontal').not($card).removeClass('horizontal');
	
			// 🔁 toggle apenas no clicado
			$card.toggleClass('horizontal');
		});
	
	});
	

	
document.addEventListener('DOMContentLoaded', function () {

	const blockFilter = document.getElementById('filtro-categorias');
	
	document.querySelectorAll('.category').forEach(category => {
		category.addEventListener('click', function (e) {
	
			const sibling = this.nextElementSibling;
			if (!sibling || !sibling.classList.contains('sibling')) return;
	
			// single NÃO abre dropdown
			if (sibling.classList.contains('single')) return;
	
			if (sibling.classList.contains('active')) {
				sibling.classList.remove('active');
				blockFilter.classList.remove('open');
			} else {
				document.querySelectorAll('.sibling').forEach(el => el.classList.remove('active'));
				sibling.classList.add('active');
				blockFilter.classList.add('open');
			}
		});
	});
	
	});

jQuery(function ($) {

	const $preview = $('.premio-hover-image');
	const $img     = $preview.find('img');

	$('.premios-grid').on('mouseenter', '.premios-row', function () {

		const img = $(this).data('image');
		if (!img) return;

		const offset = $(this).position().top + 13;

		$img.attr('src', img);
		$preview.css({
			top: offset,
			opacity: 1
		});
	});

	$('.premios-grid').on('mouseleave', '.premios-row', function () {
		$preview.css('opacity', 0);
	});

	// 🔗 clique na linha inteira
	$('.premios-grid').on('click', '.premios-row', function () {
		const link = $(this).data('link');
		if (link) {
			window.location.href = link;
		}
	});

});

jQuery(function ($) {

    function initPremiosMobile() {
        const $carousel = $('.premios-mobile-carousel');

        if (!$carousel.length) return;

        if ($(window).width() <= 768 && !$carousel.hasClass('slick-initialized')) {
            $carousel.slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                dots: true,
                arrows: false,
            });
        }

        if ($(window).width() > 768 && $carousel.hasClass('slick-initialized')) {
            $carousel.slick('unslick');
        }
    }

    initPremiosMobile();
    $(window).on('resize', initPremiosMobile);

});

jQuery(function ($) {

    $('.equipe').on('mouseenter', '.member', function () {

        const $member = $(this);
        const $grid   = $member.closest('.equipe-grid');

        $grid.find('.info-title').text($member.data('title'));
        $grid.find('.info-subtitle').html($member.data('subtitle'));
		$grid.find('.info-bio').html($member.data('bio'));

    });

});

document.querySelectorAll('.equipe-grid .member').forEach(member => {

	member.addEventListener('mouseenter', () => {
  
	  const row  = member.closest('.equipe-grid');
	  const info = row.querySelector('.equipe-info');
	  if (!info) return;
  
	  const memberRect = member.getBoundingClientRect();
	  const rowRect    = row.getBoundingClientRect();
  
	  // altura e posição iguais à imagem
	  info.style.top    = `${memberRect.top - rowRect.top}px`;
	  info.style.height = `${memberRect.height}px`;
  
	  // popula conteúdo
	  info.querySelector('.info-title').innerHTML =
		member.dataset.title || '';
  
	  info.querySelector('.info-subtitle').innerHTML  =
		member.dataset.subtitle || '';
  
	  // 🔥 AQUI É O PONTO IMPORTANTE
	  info.querySelector('.info-bio').innerHTML =
		member.dataset.bio || '';
  
	  info.classList.add('active');
	});
  
	member.addEventListener('mouseleave', () => {
	  const row  = member.closest('.equipe-grid');
	  const info = row.querySelector('.equipe-info');
	  if (!info) return;
  
	  info.classList.remove('active');
	});
  
  });
  
jQuery(function ($) {

    $('.equipe-mobile').on('click', '.read-more', function () {

        const $member = $(this).closest('.mobile-member');

        $member.toggleClass('open');

        if ($member.hasClass('open')) {
            $(this).text('Leia menos');
        } else {
            $(this).text('Leia mais');
        }

    });

});
