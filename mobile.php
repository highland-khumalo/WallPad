<?php
include "func.php";

create_table('wallpapers', $columns);

$sql = "SELECT * FROM wallpapers WHERE device = 'mobile' ORDER BY date_uploaded DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html  data-theme="light">

<?php render("head", $config['name'] . " : Get High-Quality Mobile Wallpapers Free"); ?>

<body class="bg-base-100"  data-theme="light">

    <?php render("nav", $config['name'] . " : Get High-Quality Wallpapers Free"); ?>

    <div class="hero h-[250px] " style="background-image: url(img/l.svg);">
        <div class="hero-content text-center">
            <div class="max-w-lg">
                <h1 class="text-5xl font-bold bg-base-100 p-4 rounded-md">All Mobile Wallpapers</h1>
            </div>
        </div>
    </div>

    <div class="container mx-auto p-4">


        <ul class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">



            <?php while ($row = $result->fetch_assoc()) { ?>
                <li class="bg-white p-4 rounded-md cursor-pointer">
                    <div class="" onclick="openModal('<?=$row['filename']?>', '<?=$row['category']?>', '<?=$row['title']?>')">
                        <img src="uploads/<?= $row['filename'] ?>" alt="Wallpaper" class="w-full h-auto">
                        <a href="/desktop-cart.php?cart=<?=$row['category']?>" class="hidden"> more</a>
                        
                    </div>

                </li>
            <?php } ?>
        </ul>
    </div>

    <!-- Modal for full-sized image -->
    <div id="modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-80">
        <div class="relative bg-white max-w-screen-md p-4 rounded-md">
            <img id="modal-image" src="" alt="Full Image" class="w-full h-auto">
            <button class="bg-blue-500 text-white px-4 py-2 rounded-md mt-2" id="modal-download-btn">Download</button>
            <a class="bg-yellow-500 text-white px-4 py-2 rounded-md mt-2" id="modal-more-btn"></a>
            <span class="pl-1 font-bold text-2xl mt-2" id="modal-title"></span>
            <button class="absolute top-2 right-2 p-2 rounded-md bg-red-600 text-base-100" onclick="closeModal()">Close</button>
        </div>
    </div>

    <script>
        function openModal(filename, cart, title) {
            const modal = document.getElementById('modal');
            const modalImage = document.getElementById('modal-image');
            const downloadBtn = document.getElementById('modal-download-btn');
            const moreBtn = document.getElementById('modal-more-btn');
            const moreTitle = document.getElementById('modal-title');

            modalImage.src = `uploads/${filename}`;
            moreBtn.href = `/mobile-cart.php?category=${cart}`;
            moreBtn.textContent = `View all ${cart}`;
            moreTitle.textContent = ` | ${title}`;
            downloadBtn.onclick = () => downloadImage(filename);

            modal.classList.remove('hidden');
        }

        function closeModal() {
            const modal = document.getElementById('modal');
            modal.classList.add('hidden');
        }

        function downloadImage(filename) {
            const downloadLink = document.createElement('a');
            downloadLink.href = `uploads/${filename}`;
            downloadLink.download = filename;
            downloadLink.style.display = 'none';
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }
    </script>
</body>

</html>