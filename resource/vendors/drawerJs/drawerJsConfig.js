var drawerPlugins = [
    // Drawing tools
    'Pencil',
    'Eraser',
    'Line',
    'ArrowOneSide',
    'ArrowTwoSide',
    'Triangle',
    'Rectangle',
    'Circle',
    'Polygon',
    // Drawing options
    'Color',
    'ShapeBorder',
    'BrushSize',
    'Resize',
    'ShapeContextMenu'
];
// var saveContent = function() {
//     var html = $('#canvas-editor').html();
//     localStorage.setItem('html', html);
//     localStorage.setItem('canvas_id', canvas.id);
// };
// var loadContent = function() {
//     var html = localStorage.getItem('html');
//     var canvasId = localStorage.getItem('canvas_id');
//     if(html && canvasId) {
//         $('#canvas-editor').html(html);
//         canvas.id = canvasId;
//         return true;
//     }
//     return false;
// };
// var canvas = null;
// $(document).ready(function () {
//     canvas = new DrawerJs.Drawer(null, {
//         plugins: drawerPlugins,
//         contentConfig: {
//             saveInHtml: false,
//             saveCanvasData: function(canvasId, canvasData) {
//                 localStorage.setItem('canvas_' + canvasId, JSON.stringify(canvasData));
//                 saveContent();
//             },
//             loadCanvasData: function(canvasId) {
//                 return localStorage.getItem('canvas_' + canvasId);
//             },
//             saveImageData: function(canvasId, imageData) {
//                 localStorage.setItem('image_' + canvasId, JSON.stringify(imageData));
//             },
//             loadImageData: function(canvasId) {
//                 return localStorage.getItem('image_' + canvasId);
//             }
//         }
//     }, 600, 600);
//     if(!loadContent()){
//         $('#canvas-editor').append(canvas.getHtml());
//     }
//     canvas.onInsert();
// });