var app = angular.module('customerApp', []);

app.controller('customerCtrl', function($scope, $http) {
    $scope.customer = {};
    $scope.customers = [];
    $scope.errorMessage = '';

    // Fetch customers
    $http.get('customerApi.php')
        .then(function(response) {
            $scope.customers = response.data;
        }, function(error) {
            console.log(error);
        });

    // Save customer (create or update)
    $scope.saveCustomer = function() {
        if (!$scope.customer.first_name || !$scope.customer.last_name || !$scope.customer.email) {
            $scope.errorMessage = 'All fields except Phone Number and Address are required.';
            return;
        }
        
        $scope.errorMessage = '';

        if ($scope.customer.id) {
            // Update customer
            $http.put('customerApi.php', $scope.customer)
                .then(function(response) {
                    alert('Customer updated successfully');
                    $scope.fetchCustomers();
                    $scope.customer = {};
                }, function(error) {
                    alert('Error: ' + error.data);
                });
        } else {
            // Create customer
            $http.post('customerApi.php', $scope.customer)
                .then(function(response) {
                    alert('Customer added successfully');
                    $scope.fetchCustomers();
                    $scope.customer = {};
                }, function(error) {
                    alert('Error: ' + error.data);
                });
        }
    };

    // Edit customer
    $scope.editCustomer = function(customer) {
        $scope.customer = angular.copy(customer);
    };

    // Delete customer
    $scope.deleteCustomer = function(id) {
        if (confirm('Are you sure you want to delete this customer?')) {
            $http.delete('customerApi.php', { data: { id: id }, headers: { 'Content-Type': 'application/json' } })
                .then(function(response) {
                    alert('Customer deleted successfully');
                    $scope.fetchCustomers();
                }, function(error) {
                    alert('Error: ' + error.data);
                });
        }
    };

    // Fetch customers again
    $scope.fetchCustomers = function() {
        $http.get('customerApi.php')
            .then(function(response) {
                $scope.customers = response.data;
            });
    };
});
