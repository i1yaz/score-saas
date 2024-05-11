<div  class="modal fade" id="mock-test-show" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tutoring Session Details</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <td> <strong> Location</strong></td>
                        <td id="location-text"></td>
                    </tr>
                    <tr>
                        <td> <strong> Date</strong></td>
                        <td id="date-text"></td>
                    </tr>
                    <tr>
                        <td> <strong> Proctor</strong></td>
                        <td id="proctor-text"></td>
                    </tr>
                    <tr>
                        <td> <strong> Created by</strong></td>
                        <td id="created-by-text"></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <a href="{{ route('mock-tests.show', 1) }}" class='btn btn-primary' id="dashboard-show-button">
                                <i class="far fa-edit"></i> View Details
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
