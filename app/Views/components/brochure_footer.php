<?php if ($pageTitle == "Home"): ?>
  <footer class="container">
    <p class="float-end"><a href="#" class="btn btn-primary rounded-circle"><i class="bi bi-arrow-up"></i></a></p>
    <p>&copy; 2017–<?= date("Y") ?> Green Haven Memorial Park &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
  </footer>
<?php endif; ?>

<script src="<?= base_url("js/bootstrap.bundle.min.js") ?>"></script>
<script src="<?= base_url("/js/form_validation.js") ?>"></script>
<script src="<?= base_url("/js/bootstrap-toast.js") ?>"></script>

<script>
  const toast = new Toast();

  let flashMessage = <?= json_encode(session()->getFlashdata("flash_message")) ?>;
  let errorMessage = <?= json_encode(session()->getFlashdata("error")) ?>

  if (flashMessage) {
    let icon = flashMessage.icon ?? "";
    let message = flashMessage.message ?? "";
    let title = flashMessage.title ?? "";
    let link = flashMessage.link ?? "";
    let linkText = flashMessage.link_text ?? "";

    if (icon && message && title) {
      toast.showToast(icon, message, title);

      if (link) {
        toast.showToast('<i class="bi bi-arrow-counterclockwise"></i>', linkText, "Undo", 5000, link);
      }
    }
  } else if (errorMessage) {
    let icon = '<i class="bi bi-x-lg text-danger"></i>';
    let message = errorMessage;
    let title = "Signin Failed";

    if (icon && message && title) {
      toast.showToast(icon, message, title);
    }
  }

  // const toast = new Toast();

  // let icon = <?= json_encode(isset($_SESSION["flash_message"]["icon"]) ? $_SESSION["flash_message"]["icon"] : "") ?>;
  // let message = <?= json_encode(isset($_SESSION["flash_message"]["message"]) ? $_SESSION["flash_message"]["message"] : "") ?>;
  // let title = <?= json_encode(isset($_SESSION["flash_message"]["title"]) ? $_SESSION["flash_message"]["title"] : "") ?>;
  // let link = <?= json_encode(isset($_SESSION["flash_message"]["link"]) ? $_SESSION["flash_message"]["link"] : "") ?>;
  // let linkText = <?= json_encode(isset($_SESSION["flash_message"]["link_text"]) ? $_SESSION["flash_message"]["link_text"] : "") ?>;

  // if (icon && message && title) {
  //     toast.showToast(icon, message, title);
  //     if (link) {
  //         toast.showToast('<i class="bi bi-arrow-counterclockwise"></i>', linkText, "Undo", 5000, link);
  //     }

  //     document.querySelector('.toast').addEventListener('hidden.bs.toast', function() {
  //         <?php unset($_SESSION["flash_message"]) ?>
  //     });
  // }
</script>
</body>

</html>