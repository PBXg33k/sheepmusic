#!/usr/bin/env bash

bin/console doc:data:drop --force
bin/console doc:data:create
bin/console doc:schema:update --force
bin/console fos:user:create admin admin@gmail.com test
bin/console music:import /Volumes/Data/Stack/Music --env=dev -vvv