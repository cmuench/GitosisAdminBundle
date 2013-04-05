<?php
/**
 * Copyright (c) 2012 Chistian Münch
 *
 * https://github.com/cmuench/n98-gitosis-admin
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE
 *
 * @author Christian Münch <christian@muench-worms.de>
 */

namespace N98\Gitosis\Admin\Cli\Command\Group;

use N98\Gitosis\Admin\Cli\Command\GitosisCommand;
use N98\Gitosis\Config\Group as ConfigGroup;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class AddCommand extends GitosisCommand
{
    protected function configure()
    {
        $this->setName('group:add')
             ->addArgument('name', InputArgument::REQUIRED, 'Name of new repository')
             ->addArgument('members', InputArgument::REQUIRED, 'Members of the group (comma seperated)')
             ->addArgument('writable', InputArgument::OPTIONAL, 'List of writeable repos (comma seperated)', '')
             ->addArgument('readonly', InputArgument::OPTIONAL, 'List of writeable repos (comma seperated)', '')
             ->setDescription('Add a new user group');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $group = new ConfigGroup($input->getArgument('name'));
        $group->setMembers($this->normalizeListInput($input->getArgument('members')))
              ->setWritable($this->normalizeListInput($input->getArgument('writable')))
              ->setReadonly($this->normalizeListInput($input->getArgument('readonly')));
        $this->getConfig()
             ->addGroup($group)
             ->save();
    }

}