### Docker configuration:
``.docker-compose.yaml``\
``./docker/*``

### Test cases:
``./tests/Feature/*``

### Redis/MySQL Integration:
The setup includes both Redis and MySQL, allowing flexibility in case either or both are required. Each service has a separate, abstracted repository layer, making it easy to interchange them.

However, the current setup lacks certain configurations needed for seamless switching between Redis and MySQL. In the future, with enough time, this could be managed with configurations to enable toggling between the two with a single environment variable.

### Where this could be improved?
1. As mentioned above, the data storage type could be handled through a separate configuration instead of manually moving code.
2. Test cases are very basic and lacks deeper functionality tests.
3. Move job queues to a separate workers for better vertical scaling. 