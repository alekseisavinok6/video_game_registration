<div class="modal fade" id="new_window_1" tabindex="-1" aria-labelledby="new_window_2" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="new_window_2">Add data</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="save_data.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title:</label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="specification" class="form-label">Specification:</label>
                        <textarea name="specification" id="specification" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="genders" class="form-label">Gender:</label>
                        <select name="genders" id="genders" class="form-select" required>
                            <option>Select...</option>
                            <?php while ($row_genders = $genders->fetch_assoc()) { ?>
                                <option value="<?php echo $row_genders["id"]; ?>"><?= $row_genders["names"] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image:</label>
                        <input type="file" name="image" id="image" class="form-control" accept="image/jpeg">
                    </div>
                    <div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-warning"><i class="fa-solid fa-floppy-disk"></i> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>