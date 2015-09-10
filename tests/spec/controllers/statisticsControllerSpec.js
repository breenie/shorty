(function () {
    'use strict';

    describe('Controller: StatisticsController', function () {

        // load the controller's module
        beforeEach(module('shortyApp'));

        var httpLocalBackend,
            StatisticsController,
            scope;

        // Initialize the controller and a mock scope
        beforeEach(inject(function ($controller, $rootScope) {
            scope = $rootScope.$new();
            StatisticsController = $controller('StatisticsController', {
                $scope: scope
            });
        }));

        beforeEach(inject(function ($httpBackend) {
            httpLocalBackend = $httpBackend;
        }));

        it('should have no items to start', function () {
            expect(scope.items.length).toBe(0);
        });

        it('should add items to the list', function () {

            var url = '/api/urls.json?limit=50&offset=0';
            var httpResponse = {"total":595,"request":{"offset":10,"limit":25,"direction":"desc"},"results":[{"id":"9R","url":"https:\/\/bamboo.intranet.intofilm.org\/admin\/elastic\/manageElasticInstances.action","clicks":0,"created":"2015-07-10T11:33:11+00:00"},{"id":"9Q","url":"https:\/\/www.google.co.uk\/search?q=elastic+bamboo+cannot+clone+git+project\u0026oq=elastic+bamboo+cannot+clone+git+project\u0026aqs=chrome..69i57.7503j0j4\u0026sourceid=chrome\u0026es_sm=91\u0026ie=UTF-8","clicks":0,"created":"2015-07-10T09:52:19+00:00"},{"id":"9P","url":"https:\/\/www.google.co.uk\/search?q=elastic+bamboo+cannot+clone+git+project\u0026oq=elastic+bamboo+cannot+clone+git+project\u0026aqs=chrome..69i57.7503j0j4\u0026sourceid=chrome\u0026es_sm=91\u0026ie=UTF-8","clicks":0,"created":"2015-07-10T09:51:08+00:00"},{"id":"9O","url":"https:\/\/www.google.co.uk\/search?q=elastic+bamboo+cannot+clone+git+project\u0026oq=elastic+bamboo+cannot+clone+git+project\u0026aqs=chrome..69i57.7503j0j4\u0026sourceid=chrome\u0026es_sm=91\u0026ie=UTF-8","clicks":0,"created":"2015-07-10T09:49:48+00:00"},{"id":"9N","url":"https:\/\/www.google.co.uk\/search?q=elastic+bamboo+cannot+clone+git+project\u0026oq=elastic+bamboo+cannot+clone+git+project\u0026aqs=chrome..69i57.7503j0j4\u0026sourceid=chrome\u0026es_sm=91\u0026ie=UTF-8","clicks":0,"created":"2015-07-10T09:49:09+00:00"},{"id":"9M","url":"https:\/\/www.google.co.uk\/search?q=elastic+bamboo+cannot+clone+git+project\u0026oq=elastic+bamboo+cannot+clone+git+project\u0026aqs=chrome..69i57.7503j0j4\u0026sourceid=chrome\u0026es_sm=91\u0026ie=UTF-8","clicks":0,"created":"2015-07-10T09:48:59+00:00"},{"id":"9L","url":"https:\/\/bamboo.intranet.intofilm.org\/browse\/IF-SDK-4\/log","clicks":0,"created":"2015-07-10T09:45:20+00:00"},{"id":"9K","url":"http:\/\/gruntjs.com\/sample-gruntfile","clicks":0,"created":"2015-07-04T19:37:01+00:00"},{"id":"9J","url":"http:\/\/localhost:9000\/","clicks":0,"created":"2015-07-04T19:36:36+00:00"},{"id":"9I","url":"http:\/\/localhost:9000\/","clicks":0,"created":"2015-07-04T19:35:25+00:00"},{"id":"9H","url":"http:\/\/localhost:9000\/","clicks":0,"created":"2015-07-04T19:35:16+00:00"},{"id":"9G","url":"http:\/\/www-filmclub.stage.fnuk.org\/members\/index\/my-club","clicks":0,"created":"2015-07-02T14:04:22+00:00"},{"id":"9F","url":"http:\/\/www-filmclub.stage.fnuk.org\/members\/index\/my-club","clicks":0,"created":"2015-07-02T14:04:02+00:00"},{"id":"9E","url":"http:\/\/docs.aws.amazon.com\/cli\/latest\/reference\/ec2\/create-snapshot.html","clicks":0,"created":"2015-07-01T08:34:00+00:00"},{"id":"9D","url":"https:\/\/bamboo.intranet.intofilm.org\/","clicks":0,"created":"2015-06-30T22:34:05+00:00"},{"id":"9C","url":"https:\/\/bamboo.intranet.intofilm.org\/","clicks":0,"created":"2015-06-30T22:33:33+00:00"},{"id":"9B","url":"http:\/\/www.pool.ntp.org\/en\/join.html","clicks":2,"created":"2015-06-30T22:32:32+00:00"},{"id":"9A","url":"https:\/\/bamboo.intranet.intofilm.org\/","clicks":0,"created":"2015-06-30T08:42:49+00:00"},{"id":"99","url":"https:\/\/signin.aws.amazon.com\/oauth?SignatureVersion=4\u0026X-Amz-Algorithm=AWS4-HMAC-SHA256\u0026X-Amz-Credential=AKIAIAZKVVQO5EZ75N6A\u0026X-Amz-Date=2015-06-25T07%3A58%3A24.699Z\u0026X-Amz-Signature=653df2d803a2ba5d86fffd8a6c0b204b72abbca74203e7bb10be6f938778dd26\u0026X-Amz-SignedHeaders=host\u0026client_id=arn%3Aaws%3Aiam%3A%3A015428540659%3Auser%2Frds\u0026redirect_uri=https%3A%2F%2Feu-west-1.console.aws.amazon.com%2Frds%2F%3F%26isauthcode%3Dtrue\u0026response_type=code","clicks":0,"created":"2015-06-26T16:34:33+00:00"},{"id":"98","url":"http:\/\/jira.matrix.intofilm.org\/browse\/FILMCLUB-6444","clicks":8,"created":"2015-06-24T18:24:04+00:00"},{"id":"97","url":"https:\/\/fooble.com","clicks":4,"created":"2015-06-19T15:20:28+00:00"},{"id":"96","url":"https:\/\/github.com\/rocketeers\/rocketeer\/issues\/72","clicks":0,"created":"2015-06-04T15:13:23+00:00"},{"id":"95","url":"http:\/\/jira.matrix.intofilm.org\/browse\/FILMCLUB-6371","clicks":0,"created":"2015-06-04T14:54:55+00:00"},{"id":"94","url":"http:\/\/jira.matrix.intofilm.org\/browse\/FILMCLUB-6371","clicks":0,"created":"2015-06-04T14:53:44+00:00"},{"id":"93","url":"https:\/\/api-filmclub.dev.fnuk.org\/app_dev.php\/rest\/delivery-addresses\/organization\/201013","clicks":0,"created":"2015-06-04T14:49:39+00:00"}]};
            httpLocalBackend.expectGET(url).respond(200, httpResponse);
            scope.getMore();
            httpLocalBackend.flush();
            expect(scope.items.length).toBe(25);
        });

        it('should add then remove an item from the list', function () {
            //scope.todo = 'Test 1';
            //scope.addTodo();
            //scope.removeTodo(0);
            //expect(scope.todos.length).toBe(0);
        });

    });
})();