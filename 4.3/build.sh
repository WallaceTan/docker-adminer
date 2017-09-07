version='4.3'
image_name='adminer'

echo 'Removing existing images...'
docker rmi $image_name

echo 'Building image...'
docker build -t $image_name .

echo "Removing existing images version $version..."
docker rmi $image_name:$version
echo "Tag image version to $version..."
docker tag $image_name $image_name:$version

if [ $# -eq 1 ]
then
    registry_image_name="$1/$image_name"
    docker tag $image_name $registry_image_name
    docker tag $image_name $registry_image_name:$version

    echo "Pushing image to $registry_image_name..."
    docker push $registry_image_name
    docker push $registry_image_name:$version
fi
