<!-- Node creation -->
<div class="modal fade" id="createNode" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-edit-user">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="mb-2">Add a new node!</h3>
                    <p class="text-muted">Remember to generate a strong key for the nodes connection, so now one can get
                        access to the daemon and do bad stuff.</p>
                </div>
                <form method="GET" action="/admin/nodes/new" class="row g-3">
                    <div class="col-12">
                        <label class="form-label" for="name">Name</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Node-1" required />
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="description">Description</label>
                        <input type="text" id="description" name="description" class="form-control"
                            placeholder="This is my uk node" />
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="host">Host (HTTPS REQUIRED)</label>
                        <input type="text" id="host" name="host" class="form-control"
                            placeholder="https://uk.mythicalsystems.me" required />
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="auth_key">Authentication key</label>
                        <input type="password" id="auth_key" name="auth_key" class="form-control" placeholder=""
                            required />
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" name="key" value="<?= $_COOKIE['api_key'] ?>"
                            class="btn btn-primary me-sm-3 me-1">Create new node</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                            aria-label="Close">Cancel </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>