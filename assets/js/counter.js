document.addEventListener("DOMContentLoaded",(()=>{const t=new IntersectionObserver((t=>{t.forEach((t=>{const e=t.target;e&&t.isIntersecting&&(t=>{var e,n,r,a;if(t.innerHTML===t.getAttribute("data-end"))return;let d={start:parseFloat(null!==(e=t.getAttribute("data-start"))&&void 0!==e?e:"0"),end:parseFloat(null!==(n=t.getAttribute("data-end"))&&void 0!==n?n:"0"),delay:parseInt(null!==(r=t.getAttribute("data-delay"))&&void 0!==r?r:"0")||0,duration:parseInt(null!==(a=t.getAttribute("data-duration"))&&void 0!==a?a:"0")||1},i=d.start,o=Math.ceil(1e3*d.duration/(d.end-d.start));t.innerHTML=i.toString(),setTimeout((()=>{const e=setInterval((()=>{i+=(d.end-d.start)/Math.abs(d.end-d.start),t.innerHTML=i.toString(),e&&i===d.end&&clearInterval(e)}),o)}),1e3*d.delay)})(e)}))}));[...document.querySelectorAll(".is-style-counter")].forEach((e=>{e.innerHTML="0",t.observe(e)}))}));