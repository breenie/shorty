# Shorty

A URL shortener.

## Create stack

Create the AWS resources.

```sh
$ aws cloudformation create-stack --stack-name shorty-${ENVIRONMENT_NAME} \
  --template-body file://template.yml \
  --parameters \
    ParameterKey=EnvironmentName,ParameterValue=${ENVIRONMENT_NAME} \
  --capabilities CAPABILITY_NAMED_IAM

```

### That was it!
