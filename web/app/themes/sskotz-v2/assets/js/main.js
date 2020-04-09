const toggleSwitchDesktop = document.querySelector('.switch-desktop input[type="checkbox"]'),
    toggleSwitchMobile = document.querySelector('.switch-mobile input[type="checkbox"]'),
    servertime = document.querySelectorAll("#server-time");
sidenavOpen = false;
toggleSwitchDesktop.addEventListener('change', switchTheme, false);
toggleSwitchMobile.addEventListener('change', switchTheme, false);




$(document).ready(function() {
    var aestTime = new Date().toLocaleString("en-US", { timeZone: "America/Boa_Vista" });
    aestTime = new Date(aestTime).toLocaleString("en-US", { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
    for (i = 0; i < servertime.length; i++) {
        servertime[i].innerHTML = aestTime;
    }
    const currentTheme = localStorage.getItem('theme') ? localStorage.getItem('theme') : null;
    if (currentTheme) {
        document.documentElement.setAttribute('data-theme', currentTheme);
        if (currentTheme === 'dark') {
            toggleSwitchDesktop.checked = true;
            toggleSwitchMobile.checked = true;
        }
    }
});

const interval = setInterval(() => {
    var aestTime = new Date().toLocaleString("en-US", { timeZone: "America/Boa_Vista" });
    aestTime = new Date(aestTime).toLocaleString("en-US", { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
    for (i = 0; i < servertime.length; i++) {
        servertime[i].innerHTML = aestTime;
    }
}, 1000);

window.onscroll = function() {
    menuSticky()
};

function menuSticky() {
    var navbar = document.getElementById("nav-sub");
    if (window.pageYOffset >= 0.5) {
        navbar.classList.add("scrolling");
    } else {
        navbar.classList.remove("scrolling");
    }
}

function openSidenav() {
    $(".sidenav").removeClass('closed');
    $(".sidenav").addClass('opening');
    $('.sidenav').css('left', '0');
    $('.canvas').css('margin-left', '250px');
    $('#toggle-sidenav .icon-menu').removeClass('fa-bars');
    $('#toggle-sidenav .icon-menu').addClass('fa-times');
    sidenavOpen = true;
    setTimeout(function() {
        $(".sidenav").removeClass('opening');
        $(".sidenav").addClass('opened');
    }, 1000);
}

function closeSidenav() {
    $(".sidenav").addClass('closed');
    $(".sidenav").removeClass('opened');
    $('.sidenav').css('left', '-250px');
    $('.canvas').css('margin-left', '0px');
    $('#toggle-sidenav .icon-menu').removeClass('fa-times');
    $('#toggle-sidenav .icon-menu').addClass('fa-bars');
    sidenavOpen = false;
}

function switchTheme(e) {
    if (e.target.checked) {
        document.documentElement.setAttribute('data-theme', 'dark');
        localStorage.setItem('theme', 'dark');
        document.querySelector('.switch-desktop input[type="checkbox"]').checked = true;
        document.querySelector('.switch-mobile input[type="checkbox"]').checked = true;
    } else {
        document.documentElement.setAttribute('data-theme', 'light');
        localStorage.setItem('theme', 'light');
        document.querySelector('.switch-desktop input[type="checkbox"]').checked = false;
        document.querySelector('.switch-mobile input[type="checkbox"]').checked = false;
    }
}

$("#toggle-sidenav").click(function(e) {
    if (sidenavOpen) {
        closeSidenav();
    } else {
        openSidenav();
    }
})
$('body').click(function(e) {
    if (!e.target.id == "sidenav") {
        if ($("#sidenav").hasClass("opened")) {
            closeSidenav();
        }
    }

    if (!$(e.target).closest('#sidenav').length) {
        if ($("#sidenav").hasClass("opened")) {
            closeSidenav();
        }
    }
});

$(".post-card").click(function(e) {
    window.location.assign(e.currentTarget.firstElementChild.href);
})
