const tabBtns = document?.querySelectorAll(".db-tab-btn");
const tabDivs = document?.querySelectorAll(".db-tab-div");

tabBtns?.forEach((btnItem) => {
    btnItem?.addEventListener("click", function() {
        const tabTarget = document?.querySelector(btnItem?.dataset?.tab);
        
        tabBtns?.forEach(tabBtn => tabBtn?.classList?.remove("active"));
        tabDivs?.forEach(tabDiv => tabDiv?.classList?.remove("active"));

        tabTarget?.classList?.add("active");
        btnItem?.classList?.add("active");
    })
})


// sub tabs
const subTabBtns = document?.querySelectorAll(".db-tab-sub-btn");
const subTabDivs = document?.querySelectorAll(".db-tab-sub-div");

subTabBtns?.forEach((btnItem) => {
    btnItem?.addEventListener("click", function() {
        const tabTarget = document?.querySelector(btnItem?.dataset?.tab);
        
        subTabBtns?.forEach(tabBtn => tabBtn?.classList?.remove("active"));
        subTabDivs?.forEach(tabDiv => tabDiv?.classList?.remove("active"));

        tabTarget?.classList?.add("active");
        btnItem?.classList?.add("active");
    })
})

