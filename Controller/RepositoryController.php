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

namespace N98\GitosisAdminBundle\Controller;

use N98\Gitosis\Config\Repository as GitosisRepository;
use N98\GitosisAdminBundle\Form\Repository as RepositoryForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class RepositoryController extends Controller
{
    /**
     * @Route("/repository/list", name="repository_list")
     * @Template()
     */
    public function listAction()
    {
        $config = $this->get('n98_gitosis_admin.gitosis_config');
        return array(
            'repositories' => $config->getRepositories()
        );
    }

    /**
     * @Route("/repository/create", name="repository_create")
     * @Template()
     */
    public function createAction()
    {
        $form = $this->get('form.factory')->create(new RepositoryForm());
        $request = $this->get('request');

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $data = $form->getData();

                try {
                    $repository = new GitosisRepository($data['name']);
                    $repository->setOwner($data['owner']);
                    $repository->setDaemon($data['daemon']);
                    $repository->setGitweb($data['gitweb']);

                    $config = $this->get('n98_gitosis_admin.gitosis_config');
                    $config->addRepository($repository)->save();

                    $this->get('session')->getFlashBag()->add('success', 'New repository was created.');
                } catch (\Exception $e) {
                    $this->get('session')->getFlashBag()->add('error', 'Could not save repository');
                }

                return $this->redirect($this->generateUrl('repository_list'));
            }
        }


        return array('form' => $form->createView());
    }

    /**
     * @Route("/repository/edit/{repo}", name="repository_edit")
     * @Template()
     */
    public function editAction($repo)
    {
        $repository = $this->get('n98_gitosis_admin.gitosis_config')->getRepository($repo);
        $data = array(
            'owner'  => $repository->getOwner(),
            'daemon' => $repository->getDaemon(),
            'gitweb' => $repository->getGitweb(),
        );

        $form = $this->get('form.factory')->create(new RepositoryForm(true), $data);
        $request = $this->get('request');

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $data = $form->getData();

                try {
                    $repository = new GitosisRepository($data['name']);
                    $repository->setOwner($data['owner']);
                    $repository->setDaemon($data['daemon']);
                    $repository->setGitweb($data['gitweb']);

                    $config = $this->get('n98_gitosis_admin.gitosis_config');
                    $config->addRepository($repository)->save();

                    $this->get('session')->getFlashBag()->add('success', 'New repository was created.');
                } catch (\Exception $e) {
                    $this->get('session')->getFlashBag()->add('error', 'Could not save repository');
                }

                return $this->redirect($this->generateUrl('repository_list'));
            }
        }


        return array('form' => $form->createView(), 'repo' => $repo);
    }

    /**
     * @Route("/repository/view/{repo}", name="repository_view")
     * @Template()
     */
    public function viewAction($repo)
    {
        $config = $this->get('n98_gitosis_admin.gitosis_config');
        
        return array(
            'repository'      => $config->getRepository($repo),
            'git'             => $config->getGitRepository($repo),
            'groups_write'    => $config->getWritableGroupsByRepository($repo),
            'groups_readonly' => $config->getReadonlyGroupsByRepository($repo),
            'users_write'     => $config->getWritableUsersByRepository($repo),
            'users_readonly'  => $config->getReadonlyUsersByRepository($repo),
        );
    }

    /**
     * @Route("/repository/delete/{repo}", name="repository_delete")
     */
    public function deleteAction($repo)
    {
        $this->get('n98_gitosis_admin.gitosis_config')
            ->removeRepository($repo)
            ->save();

        return $this->redirect($this->generateUrl('repository_list'));
    }
}