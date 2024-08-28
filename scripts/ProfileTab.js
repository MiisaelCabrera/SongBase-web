function showHideSection(section)
{
    var p = "p";
    document.getElementById(p.concat(section.toString())).style.display = 'flex';
    switch(section)
    {
        case 1:
            document.getElementById(p.concat("2")).style.display = 'none';
            document.getElementById(p.concat("3")).style.display = 'none';
        break;

        case 2:
            document.getElementById(p.concat("1")).style.display = 'none';
            document.getElementById(p.concat("3")).style.display = 'none';
        break;

        case 3:
            document.getElementById(p.concat("1")).style.display = 'none';
            document.getElementById(p.concat("2")).style.display = 'none';
        break;
    }
}

var currentPhoto = 0;
var total = 0;

function hidePhoto()
{
    document.getElementById('openPicture').style.display = 'none';
}

function changePhoto()
{
    var id="i";
    id = id.concat(currentPhoto.toString());
    document.getElementById('selectedImage').src = document.getElementById(id).src;
}

function showPhoto(index, photosQuantity)
{
    currentPhoto = index;
    total = photosQuantity;
    changePhoto();
    document.getElementById('openPicture').style.display = 'flex';
}

function nextPhoto()
{
    if(currentPhoto == 0)
    {
        currentPhoto = total;
    }
    else
    {
        currentPhoto--;
    }
    changePhoto();

}

function previousPhoto()
{
    if(currentPhoto == total)
    {
        currentPhoto = 0;
    }
    else
    {
        currentPhoto++;
    }

    changePhoto();

}