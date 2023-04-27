(function (d, s, id) {
    var js, pjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {
        return;
    }
    js = d.createElement(s);
    js.id = id;
    js.src = "https://d1csarkz8obe9u.cloudfront.net/plugins/editor/postermywall-editor-v3.js";
    pjs.parentNode.insertBefore(js, pjs);
}(document, 'script', 'pmw-plugin-editor'));

const openEditorButton = document.getElementById("open-editor");
const closeEditorButton = document.getElementById("close-editor");


const postermywallEditor = document.getElementById("postermywall-editor");

openEditorButton.addEventListener("click", function () {
    postermywallEditor.style.display = "block"; 
    PMW.plugin.editor.open(); 
});

// 为关闭按钮添加点击事件监听器
closeEditorButton.addEventListener("click", function () {
    PMW.plugin.editor.close(); 
    postermywallEditor.style.display = "none"; 
});