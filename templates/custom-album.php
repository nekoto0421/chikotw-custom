<div class="custom-track" style="display:none;">
    <div class="main-image">
        <img id="image1" src="" alt="" loading="lazy">
    </div>
    <div class="sub-images">
        <div class="image-row">
            <img id="image2" src="" alt="" loading="lazy">
            <img id="image3" src="" alt="" loading="lazy">
        </div>
        <!-- Add another image row for 2x2 layout -->
        <div class="image-row">
            <img id="image4" src="" alt="" loading="lazy">
            <img id="image5" src="" alt="" loading="lazy">
        </div>
    </div>
</div>
<style>
/* Reset default margin and padding */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Set up the custom-track container */
.custom-track {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

/* Set up the main image */
.main-image{
    max-height:500px;
    width:50%;
}
.main-image img {
    height: auto;
}

/* Set up the sub-images container */
.sub-images {
    display: flex;
    flex-direction: column;
    margin-left: 20px;
}

/* Set up the image rows */
.image-row {
    display: flex;
}

/* Set up the sub-images */
.image-row img {
    width: 50%;
    height: auto;
}

/* Set up the gap between images */
.image-row img:not(:last-child) {
    margin-right: 10px;
}
.image-row:not(:last-child) {
    margin-bottom: 10px;
}
.sub-images img{
    max-height:230px;
}
</style>