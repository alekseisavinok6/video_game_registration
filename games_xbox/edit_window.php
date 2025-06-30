<div class="modal fade" id="edit_window_1" tabindex="-1" aria-labelledby="edit_window_2" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="edit_window_2">Edit data</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="update_data.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title:</label>
                        <input type="text" name="title" id="title" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label for="specification" class="form-label">Specification:</label>
                        <textarea name="specification" id="specification" class="form-control form-control-sm" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="genders" class="form-label">Gender:</label>
                        <select name="genders" id="genders" class="form-select form-select-sm" required>
                            <option value="">Select...</option>
                            <?php while ($row_genders = $genders->fetch_assoc()) { ?>
                                <option value="<?php echo $row_genders["id"]; ?>"><?= $row_genders["names"] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-2">
                        <img id="img_image" width="100" class="img-thumbnail">
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image:</label>
                        <input type="file" name="image" id="image" class="form-control form-control-sm" accept="image/jpeg">
                    </div>
                    <div class="d-flex justify-content-end pt2">
                        <button type="button" class="btn btn-secondary me-1" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-warning ms-1"><i class="fa-solid fa-floppy-disk"></i> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>