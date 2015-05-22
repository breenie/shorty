function ShortyController($scope, Url) {

    var currentResource;
    var resetForm = function () {
        $scope.addMode = true;
        $scope.author = undefined;
        $scope.message = undefined;
        $scope.selectedIndex = undefined;
    }

    $scope.hostname = document.location.host;
    $scope.urls = Url.query();
    $scope.addMode = true;

    $scope.add = function () {
        var key = {};
        var value = {author: $scope.author, message: $scope.message}

        Url.save(key, value, function (data) {
            $scope.urls.push(data);
            resetForm();
        });
    };

    $scope.update = function () {
        var key = {id: currentResource.id};
        var value = {author: $scope.author, message: $scope.message}
        Url.save(key, value, function (data) {
            currentResource.author = data.author;
            currentResource.message = data.message;
            resetForm();
        });
    }

    $scope.refresh = function () {
        $scope.urls = Url.query();
        resetForm();
    };

    $scope.deleteMessage = function (index, id) {
        Url.delete({id: id}, function () {
            $scope.urls.splice(index, 1);
            resetForm();
        });
    };

    $scope.selectMessage = function (index) {
        currentResource = $scope.urls[index];
        $scope.addMode = false;
        $scope.author = currentResource.author;
        $scope.message = currentResource.message;
    }

    $scope.cancel = function () {
        resetForm();
    }
}
