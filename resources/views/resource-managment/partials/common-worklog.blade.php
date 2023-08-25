<div class="row">
    <div class="col-md-12 mt-2">
        <div class="card emp-list-section ">
            <div class="card-body">
                <div class="table-responsive fixed-header">
                    <input type="hidden" value="1" id="findDataFlag">
                    <input type="hidden" value="dailyWorkLog" id="checkPage">
                    <table class="table table-centered table-bordered mb-0 table-hover" id="dailyWorkLogTable">
                        <thead>
                            <tr>
                                <th class="stick-column col-no-1 p-1 bg-white select-project-team">
                                    <input type="text" placeholder="Search Employees..." id="userName" class="form-control border-0" />
                                </th>
                                <th class="stick-column col-no-2">Total</th>
                                <th class="stick-column col-no-3">Avg</th>
                                @foreach($range as $key=> $val)
                                    <th class="date-cell">{{$key}}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody id="allDailyWorkLog"></tbody>
                    </table>
                </div>
                <!-- Data Loader -->
                <div class="auto-load text-center">
                    <svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
                        y="0px" height="60" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                        <path fill="#000"
                            d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                            <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s" from="0 50 50"
                                to="360 50 50" repeatCount="indefinite" />
                        </path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>
