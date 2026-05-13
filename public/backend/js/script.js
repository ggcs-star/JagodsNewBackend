"use strict";

//prealoader js
window.addEventListener("load", function(){
    var preload = document.querySelector(".preloader");
    preload?.classList.add("finish");
});


// For sticky header
window?.addEventListener("scroll", function () {
    const headerElement = document?.querySelector(".ff-header");
    const windowScroll = this?.scrollY;

    if (windowScroll > 0) headerElement?.classList?.add("active");
    else headerElement?.classList?.remove("active");
});

// For admin responsive multiple header
function scrollUpDown() {
    let mainHeader = document?.querySelector(".db-header");
    let subHeader = document?.querySelector(".sub-header");
    let mainHeight = mainHeader?.scrollHeight;
    let lastScroll = 0;

    if (subHeader) {
        subHeader.style.top = `${mainHeight}px`;

        window?.addEventListener("scroll", function () {
            let firstScroll = window?.pageYOffset || this?.document?.documentElement?.scrollTop;

            if (firstScroll > lastScroll) subHeader.style.top = `-${mainHeight}px`;
            else subHeader.style.top = `${mainHeight}px`;

            lastScroll = firstScroll
        })
    }
}
scrollUpDown();


// sidebar open & closing
const sidebarBtn = document?.querySelector(".db-header-nav");
const sidebarDiv = document?.querySelector(".db-sidebar");
const mainDiv = document?.querySelector(".db-main");

sidebarBtn?.addEventListener("click", function () {
    const checkIcon = sidebarBtn?.className?.includes("fa-bars");

    if (!checkIcon) {
        sidebarBtn?.classList?.replace("fa-align-left", "fa-bars");
        sidebarDiv?.classList?.add("active");
        mainDiv?.classList?.add("expand");
    }
    else {
        sidebarBtn?.classList?.replace("fa-bars", "fa-align-left");
        sidebarDiv?.classList?.remove("active");
        mainDiv?.classList?.remove("expand");
    }
})

document?.querySelectorAll(".xmark-btn")?.forEach((closeBtn) => {
    closeBtn?.addEventListener("click", function () {
        sidebarBtn?.classList?.replace("fa-bars", "fa-align-left");
        sidebarDiv?.classList?.remove("active");
        mainDiv?.classList?.remove("expand");
    })
})


// table filter open & closing
const tableFilterBtn = document?.querySelector(".table-filter-btn");
const tableFilterDiv = document?.querySelector(".table-filter-div");

tableFilterBtn?.addEventListener("click", function () {
    const check = tableFilterDiv?.className?.includes("active");
    const change = this?.querySelector(".fa-solid");

    if (!check) {
        tableFilterDiv?.classList?.add("active");
        tableFilterDiv.style.height = `${tableFilterDiv.scrollHeight}px`
        change?.classList?.replace("fa-filter", "fa-xmark");
    }
    else {
        tableFilterDiv?.classList?.remove("active");
        tableFilterDiv.style.height = "0px",
            change?.classList?.replace("fa-xmark", "fa-filter");
    }
})


function sidebarMenu() {
    let sidebarItems = document?.querySelectorAll(".db-sidebar-nav-item");
    let sidebarDropdowns = document?.querySelectorAll(".db-sidebar-nav-dropdown");
    let itemValue = false;

    sidebarItems?.forEach((sidebarItem) => {
        if (sidebarItem?.querySelector(".db-sidebar-nav-dropdown")) {
            sidebarItem?.querySelector(".db-sidebar-nav-menu")?.classList?.add("downarrow");
        }

        sidebarItem?.addEventListener("click", function () {
            let currentDropdown = sidebarItem?.querySelector(".db-sidebar-nav-dropdown");

            sidebarItems?.forEach(allItems => allItems?.classList?.remove("active"));
            sidebarItem?.classList?.add("active");

            if (currentDropdown) {
                sidebarDropdowns?.forEach(allDropdowns => allDropdowns.style.height = "0px");

                itemValue = !itemValue

                if (itemValue) {
                    currentDropdown.style.height = `${currentDropdown.scrollHeight}px`;
                }
                else {
                    sidebarItem?.classList?.remove("active");
                    currentDropdown.style.height = "0px";
                }
            }
        })
    })
}
sidebarMenu();


function openClose(dataAttr, attrName, dataName, closeClass) {
    let openBtn = document?.querySelector(dataAttr);
    let targetElm = document?.querySelector(openBtn?.dataset[attrName]);
    let closeBtn = targetElm?.querySelector(closeClass);

    const openFunc = () => {
        targetElm?.classList?.add(dataName);
        document.body.classList.add("overflow-hidden");
    }

    const closeFunc = () => {
        targetElm?.classList?.remove(dataName);
        document.body.classList.remove("overflow-hidden");
    }

    openBtn?.addEventListener("click", openFunc);
    closeBtn?.addEventListener("click", closeFunc);
}
openClose("[data-webcart]", "webcart", "active", ".xmark-btn");
openClose("[data-mobcart]", "mobcart", "active", ".xmark-btn");
openClose("[data-account]", "account", "active", ".xmark-btn");
openClose("[data-profile]", "profile", "active", ".xmark-btn");


function cartCounter() {
    const indecGroup = document?.querySelectorAll(".indec-group");

    indecGroup?.forEach((groupItem) => {
        const indecPlus = groupItem?.querySelector(".indec-plus");
        const indecValue = groupItem?.querySelector(".indec-value");
        const indecMinus = groupItem?.querySelector(".indec-minus");

        if (indecValue?.value != 1) indecMinus?.classList?.replace("fa-trash-can", "fa-minus");
        else indecMinus?.classList?.replace("fa-minus", "fa-trash-can");

        indecPlus?.addEventListener("click", function () {
            indecValue.value++
            if (indecValue?.value > 1) {
                indecMinus?.classList?.replace("fa-trash-can", "fa-minus");
            }
        })

        indecMinus?.addEventListener("click", function () {
            if (indecValue?.value >= 2) {
                indecValue.value--
                if (indecValue?.value == 1) {
                    indecMinus?.classList?.replace("fa-minus", "fa-trash-can");
                }
            }
        })
    })
}
cartCounter();


function variationSize() {
    const sizeTabs = document?.querySelector(".size-tabs");
    const sizeTab = sizeTabs?.querySelectorAll("label");

    sizeTab?.forEach((item) => {
        item?.addEventListener("click", function () {
            sizeTab?.forEach(allItem => allItem?.classList?.remove("active"));
            item?.classList?.add("active");
        })
    })
}
variationSize();


function singleGroupActive(parentClass, addedClass) {
    const singleElements = document?.querySelectorAll(parentClass);

    singleElements?.forEach((singleElement) => {
        for (let i = 0; i < singleElement.childElementCount; i++) {
            singleElement?.children[i]?.addEventListener("click", function () {
                for (let a = 0; a < singleElement.childElementCount; a++) singleElement?.children[a]?.classList?.remove(addedClass);
                singleElement?.children[i]?.classList?.add(addedClass);
            })
        }
    })
}
singleGroupActive(".db-message-list", "active");
singleGroupActive(".dimension-navs", "dimension-active");
singleGroupActive(".menu-slides", "menu-category-active");
singleGroupActive(".branch-navs", "active");
singleGroupActive(".active-group", "active");
singleGroupActive(".payment-group", "border-primary");
singleGroupActive(".payment-fieldset", "active");
singleGroupActive(".pos-group", "active");


function singleSlideDown(dataAttr, attrName, toggleClass) {
    const btnElement = document?.querySelector(dataAttr);
    const tabElement = document?.querySelector(btnElement?.dataset[attrName]);

    document?.addEventListener("click", function (event) {

        if (btnElement && tabElement) {
            if (!btnElement?.contains(event?.target)) {
                if (!tabElement?.contains(event?.target)) {
                    btnElement?.classList?.remove(toggleClass);
                    tabElement.style.height = "0px";
                }
            }
            else {
                btnElement?.classList?.add(toggleClass);
                tabElement.style.height = `${tabElement?.scrollHeight}px`;
            }
        }
    })
}
singleSlideDown("[data-paycard]", "paycard", "active");
singleSlideDown("[data-label]", "label", "active");
singleSlideDown("[data-terms1]", "terms1", "active");
singleSlideDown("[data-terms2]", "terms2", "active");
singleSlideDown("[data-settings]", "settings", "active");


function questionPaper(dataAttr, attrName, toggleClass) {
    let btnElement = document?.querySelector(dataAttr);
    let iconElement = btnElement?.querySelector('.fa-chevron-down');
    let tabElement = document?.querySelector(btnElement?.dataset[attrName]);
    let toggleValue = false

    document?.addEventListener("click", function (event) {
        toggleValue = !toggleValue

        if (btnElement && tabElement) {
            if (!btnElement?.contains(event?.target)) {
                btnElement?.classList?.remove(toggleClass);
                iconElement.style.transform = 'rotate(0deg)'
                tabElement.style.height = "0px";
            }
            else {
                if(toggleValue) {
                    btnElement?.classList?.add(toggleClass);
                    iconElement.style.transform = 'rotate(180deg)'
                    tabElement.style.height = `${tabElement?.scrollHeight}px`;
                }
                else {
                    iconElement.style.transform = 'rotate(0deg)'
                    btnElement?.classList?.remove(toggleClass);
                    tabElement.style.height = "0px";
                }
            }
        }
    })
}
questionPaper("[data-questions]", "questions", "active");


function multipleTabs(btnName, tabName, toggleClass) {
    const tabBtns = document?.querySelectorAll(btnName);
    const tabDivs = document?.querySelectorAll(tabName);

    tabBtns?.forEach((btnItem) => {
        btnItem?.addEventListener("click", function () {
            const targetDiv = document?.querySelector(btnItem?.dataset?.tab);

            tabBtns?.forEach(tabBtn => tabBtn?.classList?.remove(toggleClass));
            tabDivs?.forEach(tabDiv => tabDiv?.classList?.remove(toggleClass));

            targetDiv?.classList?.add(toggleClass);
            btnItem?.classList?.add(toggleClass);
        })
    })
}
multipleTabs(".db-tabBtn", ".db-tabDiv", "active");
multipleTabs(".profile-tabBtn", ".profile-tabDiv", "active");


function posResponsiveCart() {
    const cartDiv = document?.querySelector(".db-pos-cartDiv");
    const cartBtn = document?.querySelector(".db-pos-cartBtn");
    const cartCls = document?.querySelector(".db-pos-cartCls");

    cartBtn?.addEventListener("click", () => cartDiv?.classList?.add("active"));
    cartCls?.addEventListener("click", () => cartDiv?.classList?.remove("active"));
}
posResponsiveCart();


function cookiePaper() {
    const cookieElement = document?.querySelector(".cookie-paper");
    const cookieCancel = document?.querySelector(".cookie-cancel");

    window?.addEventListener("load", function () {
        this.setTimeout(() => {
            cookieElement?.classList?.add("active");
        }, 1500);
    });

    cookieCancel?.addEventListener("click", function () {
        cookieElement?.classList?.remove("active");
    })
}
// cookiePaper();


function searchCancel() {
    const srcBtn = document?.querySelectorAll(".header-search-button");

    srcBtn?.forEach((btnItem) => {
        btnItem?.addEventListener("click", function () {
            const srcFld = btnItem?.parentElement?.querySelector(".header-search-field");
            srcFld.value = "";
        })
    })
}
searchCancel();


function vegNavs() {
    let vegNavs = document?.querySelector(".veg-navs");

    for (let i = 0; i < vegNavs?.childElementCount; i++) {
        let navItem = vegNavs?.children[i];
        let navValue = false;

        navItem?.addEventListener("click", function () {
            for (let a = 0; a < vegNavs.childElementCount; a++) vegNavs?.children[a]?.classList?.remove("veg-active");

            navValue = !navValue

            if (navValue) navItem?.classList?.add("veg-active");
            else navItem?.classList?.remove("veg-active");
        })
    }
}
vegNavs();


function groupCheck(targetElement, toggleClass) {
    let toggleElement = document?.querySelectorAll(targetElement);

    toggleElement?.forEach((itemElement) => {
        itemElement?.addEventListener("click", function () {
            let checkInput = itemElement?.querySelector("input");
            itemElement?.classList?.toggle(toggleClass);

            if (!checkInput.checked) checkInput.checked = true
            else checkInput.checked = false
        })
    })
}
groupCheck(".extra", "active");
groupCheck(".addon", "active");


$(document).ready(function () {
    $(".char-limit").each((index, char) => {
        let charLimit = 65;
        let charArray = char.innerText.split('');
        let charText = charArray.slice(0, charLimit).join("") + '...';
        char.innerText = charText;
    })
});

function chatFile() {

}


$(document).ready(function () {
    $(".chat-footer").each((index, chatbox) => {
        let fileInput = $(chatbox).find(".chat-footer-file-input");
        let fileList = $(chatbox).find(".chat-footer-data-list");

        fileInput.change(function (event) {
            let fileObj = event.target.files;
            let chatList = $(chatbox.parentElement).find(".chat-list");
            chatList[0].classList.add('change');

            fileList.each((listIndex, listItem) => {
                listItem.classList.remove("hidden");
            })

            for (let i = 0; i < fileObj.length; i++) {
                let fileName = fileObj[i].name.slice(0, 10) + '...';
                let fileItem = `<li class="chat-footer-data-item">
                                    <i class="fa-solid fa-file-lines"></i>
                                    <span>${fileName}</span>
                                    <button class="fa-solid fa-circle-xmark" onClick="removeFile(this)" type="button"></button>
                                </li>`
                fileList.append(fileItem);
            }
        });
    })
})

function removeFile(element) {
    element.parentElement.remove();
    let fileItem = $(".chat-footer-data-item");
    let fileList = $(".chat-footer-data-list");
    if (fileItem.length == 0) {
        fileList[0].classList.add('hidden');
        $(".chat-list").each((i, e) => {
            e.classList.remove('change');
        })
    }
}

$(function() {
    $('.custom-switch').each(function(index, item) {
        $(item).children('input').on('change', function() {
            let isChecked = $(this).prop('checked');
            if(isChecked) $(this).next().text('on');
            else $(this).next().text('off');
        })
    })
})


$(function() {
    let toggleValue = false;

    $('.settings-btn').on('click', function() {
        toggleValue = !toggleValue
        const icon = document.querySelector('.setting-btn-icon')
        const pixel = $(this).siblings().prop('scrollHeight');

        if(toggleValue) {
            $(this).siblings().css('height', `${pixel}px`);
            icon.classList.add('fa-chevron-up')
            icon.classList.remove('fa-chevron-down')
        }
        else {
            $(this).siblings().css('height', `0px`);
            icon.classList.add('fa-chevron-down')
            icon.classList.remove('fa-chevron-up')
        }
    })
})

const handlePrev = (param) => {
    const stepGroup = document.querySelector('#step-group');
    const stepBtn = document.querySelector(param);

    Array.from(stepGroup.children).map((elem)=> {
        elem.classList.replace('block', 'hidden');
    });

    stepBtn.classList.replace('hidden', 'block');
}

const handleNext = (param) => {
    const stepGroup = document.querySelector('#step-group');
    const stepBtn = document.querySelector(param);

    Array.from(stepGroup.children).map((elem)=> {
        elem.classList.replace('block', 'hidden');
    });

    stepBtn.classList.replace('hidden', 'block');
}


// For language name and flag dropdown selection
function dropdownSelection(dropdown, selection) {
    let selectElement = document?.querySelectorAll(dropdown);
    let htmlElement = document?.querySelector('html');
    let toggleValue = false;

    selectElement?.forEach((selectItem) => {

        let buttonElement = selectItem?.firstElementChild;
        let paperElement = selectItem?.lastElementChild;

        let currImg = buttonElement?.querySelector("img");
        let currSpan = buttonElement?.querySelector("span");

        // get defult value from local storage
        let currFlagSource = localStorage.getItem('flagSource');
        let currLangName = localStorage.getItem('langName');
        let currDirName = localStorage.getItem('dirName');

        // set default value or localstorage value
        currImg.src = currFlagSource || '../../images/flag/united-states.png'
        currSpan.innerText = currLangName || 'english'
        htmlElement?.setAttribute('dir', currDirName || 'ltr');

        document?.addEventListener("click", function(event) {

            if(!selectItem?.contains(event?.target)) {
                toggleValue = false;
                buttonElement?.classList?.remove("active");
                paperElement?.classList?.remove("active");
            }
            else {
                if(!buttonElement) {
                    buttonElement?.classList?.remove("active");
                    paperElement?.classList?.remove("active");
                }
                else {
                    toggleValue = !toggleValue

                    if(toggleValue) {
                        buttonElement?.classList?.add("active");
                        paperElement?.classList?.add("active");
                    }
                    else {
                        buttonElement?.classList?.remove("active");
                        paperElement?.classList?.remove("active");
                    }
                }
            }
        })

        if(selection) {
            for(let i = 0; i < paperElement?.children?.length; i++) {
                paperElement?.children[i]?.addEventListener("click", function(event) {

                    let selectSrc = this?.querySelector("img")?.getAttribute("src");
                    let selectText = this?.querySelector("span")?.textContent;
                    let selectDir = this.getAttribute("data-dir");

                    localStorage.setItem('dirName', selectDir);
                    localStorage.setItem('langName', selectText);
                    localStorage.setItem('flagSource', selectSrc);

                    currImg.src = selectSrc
                    currSpan.innerText = selectText
                    htmlElement?.setAttribute('dir', selectDir);
                })
            }
        }
    })
}
dropdownSelection(".language-group", true);