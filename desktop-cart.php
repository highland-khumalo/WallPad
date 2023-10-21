<?php
include "func.php";

if (isset($_GET['category'])) {

    $category = $_GET['category'];

    $stmt = $conn->prepare("SELECT * FROM wallpapers WHERE device = 'desktop' AND category = ?");
    $stmt->bind_param('s', $category);
    $stmt->execute();
    $result = $stmt->get_result();

?>
    <!DOCTYPE html>
    <html  data-theme="light">

    <?php render("head", $category . " Wallpapers HD Quality Free PC | " . $config['name']); ?>

    <body class="bg-base-100"  data-theme="light">

        <?php render("nav", $config['name'] . " : Get High-Quality Wallpapers Free"); ?>

        <div class="hero h-[250px] " style="background-image: url(img/l.svg);">
            <div class="hero-content text-center">
                <div class="max-w-lg">
                    <h1 class="text-5xl font-bold bg-base-100 p-4 rounded-md"><?= $category ?> Wallpapers HD Quality Free</h1>
                </div>
            </div>
        </div>


        <div class="container mx-auto p-4">
            <ul class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php

                while ($row = $result->fetch_assoc()) { ?>
                    <li class="bg-white p-4 rounded-md cursor-pointer" onclick="openModal('<?=$row['filename']?>', '<?=$row['category']?>', '<?=$row['title']?>')">
                        <img src="uploads/<?php echo $row['filename']; ?>" alt="<?= $row['title'] ?>" class="w-full h-auto">
                    </li>
            <?php }
                $stmt->close();
                $conn->close();
            }
            ?>
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
                moreBtn.href = `/desktop-cart.php?category=${cart}`;
                moreTitle.textContent = ` | ${title}`;
                downloadBtn.onclick = () => downloadImage(filename);

                modal.classList.remove('hidden');
            }

            function closeModal() {
                const modal = document.getElementById('modal');
                modal.classList.add('hidden');
            }

            function downloadImage(filename) {
                // Implement your image download logic here
                // You can use JavaScript to trigger the download or link to a download URL.
                // For example, you can create an anchor element and trigger a click event.
                const downloadLink = document.createElement('a');
                downloadLink.href = `download.php?filename=${filename}`;
                downloadLink.download = filename;
                downloadLink.style.display = 'none';
                document.body.appendChild(downloadLink);
                downloadLink.click();
                document.body.removeChild(downloadLink);
            }
        </script>
    </body>

    </html>