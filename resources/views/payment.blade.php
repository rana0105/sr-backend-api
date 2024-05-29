@section('content')
    <form id="myForm" action="{{ route('makePayment.store') }}" method="POST">
        <!-- Your form fields here -->
        @csrf
        <button type="submit" id="submitButton" style="display: none;"></button>
    </form>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('submitButton').click();
        });
    </script>
@endsection
