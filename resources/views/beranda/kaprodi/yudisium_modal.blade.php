<!-- Modal Yudisium List -->
<div class="modal fade" id="modalYudisiumList" tabindex="-1" role="dialog" aria-labelledby="modalYudisiumListLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalYudisiumListLabel">
          List KoTA Yudisium <span id="modalYudisiumType"></span>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead class="thead-light">
              <tr>
                <th>Nama KoTA</th>
                <th>Judul</th>
              </tr>
            </thead>
            <tbody id="modalYudisiumTableBody">
              <!-- Data akan diisi via JavaScript -->
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>