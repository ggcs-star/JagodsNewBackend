"use strict";

const drawerDivs = document?.querySelectorAll(".drawer");
const drawerSets = document?.querySelectorAll("[data-drawer]");

drawerSets?.forEach((drawerSet) => {
    drawerSet?.addEventListener("click", function() {
        const targetElm = document?.querySelector(drawerSet?.dataset?.drawer);
        
        drawerSets?.forEach(drawerBtn => drawerBtn?.classList?.remove("active"));
        drawerDivs?.forEach(drawerDiv => drawerDiv?.classList?.remove("active"));

        targetElm?.classList?.add("active");
        drawerSet?.classList?.add("active");
        document.body.classList.add("overflow-hidden");
    })
})

document?.querySelectorAll(".drawer-close")?.forEach((closeBtn) => {
    closeBtn?.addEventListener("click", function() {
        drawerSets?.forEach(drawerBtn => drawerBtn?.classList?.remove("active"));
        drawerDivs?.forEach(drawerDiv => drawerDiv?.classList?.remove("active"));;
        document.body.classList.remove("overflow-hidden");
    })
})