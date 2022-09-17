<?php

namespace Labstag\Command;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\Groupe;
use Labstag\Entity\User;
use Labstag\Lib\CommandLib;
use Labstag\Repository\GroupeRepository;
use Labstag\Repository\UserRepository;
use Labstag\RequestHandler\UserRequestHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Workflow\Registry;

class LabstagUserCommand extends CommandLib
{

    /**
     * @var string
     */
    protected static $defaultName = 'labstag:user';

    public function __construct(
        EntityManagerInterface $entityManager,
        protected Registry $registry,
        protected UserRequestHandler $userRequestHandler,
        protected GroupeRepository $groupeRepository,
        protected UserRepository $userRepository
    )
    {
        parent::__construct($entityManager);
    }

    protected function actionEnableDisableDelete(InputInterface $input, OutputInterface $output, $inputOutput, $action): void
    {
        $helper         = $this->getHelper('question');
        $choiceQuestion = new ChoiceQuestion(
            "Entrer le username de l'utilisateur : ",
            $this->tableQuestionUser()
        );
        $choiceQuestion->setMultiselect(true);

        $usernames = $helper->ask($input, $output, $choiceQuestion);
        foreach ($usernames as $username) {
            if ('' == $username) {
                continue;
            }

            switch ($action) {
                case 'enable':
                    $this->enable($helper, $username, $inputOutput, $input, $output);

                    break;
                case 'disable':
                    $this->disable($helper, $username, $inputOutput, $input, $output);

                    break;
                case 'delete':
                    $this->delete($helper, $username, $inputOutput, $input, $output);

                    break;
            }
        }
    }

    protected function actionState(InputInterface $input, OutputInterface $output, $inputOutput): void
    {
        $helper         = $this->getHelper('question');
        $choiceQuestion = new ChoiceQuestion(
            "Entrer le username de l'utilisateur : ",
            $this->tableQuestionUser()
        );
        $username       = $helper->ask($input, $output, $choiceQuestion);
        $this->state($helper, $username, $inputOutput, $input, $output);
    }

    protected function actionUpdatePassword(InputInterface $input, OutputInterface $output, $inputOutput): void
    {
        $helper         = $this->getHelper('question');
        $choiceQuestion = new ChoiceQuestion(
            "Entrer le username de l'utilisateur : ",
            $this->tableQuestionUser()
        );
        $username       = $helper->ask($input, $output, $choiceQuestion);
        $this->updatePassword($helper, $username, $inputOutput, $input, $output);
    }

    protected function configure(): void
    {
        $this->setDescription('command for admin user');
    }

    protected function create($helper, $inputOutput, InputInterface $input, OutputInterface $output): void
    {
        $inputOutput = new SymfonyStyle($input, $output);
        $user        = new User();
        $old         = clone $user;
        $question    = new Question("Entrer le username de l'utilisateur : ");
        $username    = $helper->ask($input, $output, $question);
        $user->setUsername($username);
        $question = new Question("Entrer le password de l'utilisateur : ");
        $question->setHidden(true);

        $password1 = $helper->ask($input, $output, $question);
        $question  = new Question("Resaisir le password de l'utilisateur : ");
        $question->setHidden(true);

        $password2 = $helper->ask($input, $output, $question);
        if ($password1 !== $password2) {
            $inputOutput->error('Mot de passe incorrect');

            return;
        }

        $user->setPlainPassword($password1);
        $question = new Question("Entrer l'email de l'utilisateur : ");
        $email    = $helper->ask($input, $output, $question);
        $user->setEmail($email);
        $groupes = $this->groupeRepository->findBy([], ['name' => 'DESC']);
        $data    = [];
        foreach ($groupes as $groupe) {
            // @var Groupe $groupe
            if ('visiteur' == $groupe->getCode()) {
                continue;
            }

            $data[$groupe->getCode()] = $groupe->getName();
        }

        $question  = new ChoiceQuestion(
            "Groupe à attribuer à l'utilisateur",
            $data
        );
        $selection = $helper->ask($input, $output, $question);
        foreach ($groupes as $groupe) {
            // @var Groupe $groupe
            if ($selection != $groupe->getCode()) {
                continue;
            }

            $user->setRefgroupe($groupe);
        }

        $this->userRequestHandler->handle($old, $user);
        $inputOutput->success('Utilisateur ajouté');
    }

    protected function delete($helper, string $username, $inputOutput, InputInterface $input, OutputInterface $output): void
    {
        $entity = $this->userRepository->findOneBy(['username' => $username]);
        if (!$entity instanceof User || is_null($entity)) {
            $inputOutput->warning(
                ['Utilisateur introuvable']
            );

            return;
        }

        $choiceQuestion = new ChoiceQuestion(
            "Êtes-vous sûr de bien vouloir supprimer l'utilisateur ".$username.' ?',
            [
                'non' => 'non',
                'oui' => 'oui',
            ]
        );

        $action = $helper->ask($input, $output, $choiceQuestion);
        if ('oui' !== $action) {
            return;
        }

        $old = clone $entity;
        $this->userRepository->remove($entity);
        $this->userRequestHandler->handle($old, $entity);
        $inputOutput->success('Utilisateur supprimé');
    }

    protected function disable($helper, $username, $inputOutput, InputInterface $input, OutputInterface $output): void
    {
        $entity = $this->userRepository->findOneBy(['username' => $username]);
        if (!$entity instanceof User || is_null($entity)) {
            $inputOutput->warning(
                ['Utilisateur introuvable']
            );

            return;
        }

        $choiceQuestion = new ChoiceQuestion(
            "Êtes-vous sûr de bien vouloir désactiver l'utilisateur ".$username.' ?',
            [
                'non' => 'non',
                'oui' => 'oui',
            ]
        );

        $action = $helper->ask($input, $output, $choiceQuestion);
        if ('oui' !== $action || !$this->registry->has($entity)) {
            $inputOutput->warning(
                ['Action impossible']
            );

            return;
        }

        $workflow = $this->registry->get($entity);
        if (!$workflow->can($entity, 'desactiver')) {
            $inputOutput->warning(
                ['Action impossible']
            );

            return;
        }

        $old = clone $entity;
        $workflow->apply($entity, 'desactiver');
        $this->entityManager->flush();
        $this->userRequestHandler->handle($old, $entity);
        $inputOutput->success('Utilisateur désactivé');
    }

    protected function enable($helper, $username, $inputOutput, InputInterface $input, OutputInterface $output): void
    {
        $entity = $this->userRepository->findOneBy(['username' => $username]);
        if (!$entity instanceof User || is_null($entity)) {
            $inputOutput->warning(
                ['Utilisateur introuvable']
            );

            return;
        }

        $choiceQuestion = new ChoiceQuestion(
            "Êtes-vous sûr de bien vouloir activer l'utilisateur ".$username.' ?',
            [
                'non' => 'non',
                'oui' => 'oui',
            ]
        );

        $action = $helper->ask($input, $output, $choiceQuestion);
        if ('oui' !== $action || !$this->registry->has($entity)) {
            $inputOutput->warning(
                ['Action impossible']
            );

            return;
        }

        $workflow = $this->registry->get($entity);
        if (!$workflow->can($entity, 'activer')) {
            $inputOutput->warning(
                ['Action impossible']
            );

            return;
        }

        $old = clone $entity;
        $workflow->apply($entity, 'activer');
        $this->entityManager->flush();
        $this->userRequestHandler->handle($old, $entity);
        $inputOutput->success('Utilisateur activé');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle   = new SymfonyStyle($input, $output);
        $helper         = $this->getHelper('question');
        $choiceQuestion = new ChoiceQuestion(
            'Action à effectué',
            [
                'list'           => 'list',
                'create'         => 'create',
                'enable'         => 'enable',
                'disable'        => 'disable',
                'delete'         => 'delete',
                'state'          => 'state',
                'updatepassword' => 'updatepassword',
            ]
        );

        $action = $helper->ask($input, $output, $choiceQuestion);
        match ($action) {
            'list' => $this->list($symfonyStyle, $output),
            'create' => $this->create($helper, $symfonyStyle, $input, $output),
            'updatepassword' => $this->actionUpdatePassword($input, $output, $symfonyStyle),
            'state' => $this->actionState($input, $output, $symfonyStyle),
            'enable', 'disable', 'delete' => $this->actionEnableDisableDelete($input, $output, $symfonyStyle, $action),
        };

        return Command::SUCCESS;
    }

    protected function list($inputOutput, OutputInterface $output): void
    {
        $users = $this->userRepository->findBy([], ['username' => 'ASC']);
        $table = [];
        foreach ($users as $user) {
            // @var User $user
            $table[] = [
                'username' => $user->getUsername(),
                'email'    => $user->getEmail(),
                'groupe'   => $user->getRefgroupe()->getName(),
                'state'    => $user->getState(),
            ];
        }

        $inputOutput->table(
            [
                'username',
                'email',
                'groupe',
                'state',
            ],
            $table
        );
        $output->writeln('list');
    }

    protected function state($helper, $username, $inputOutput, InputInterface $input, OutputInterface $output): void
    {
        $entity = $this->userRepository->findOneBy(['username' => $username]);
        if (!$entity instanceof User || is_null($entity)) {
            $inputOutput->warning(
                ['Utilisateur introuvable']
            );

            return;
        }

        $states      = [];
        $workflow    = $this->registry->get($entity);
        $transitions = $workflow->getEnabledTransitions($entity);
        foreach ($transitions as $transition) {
            $name          = $transition->getName();
            $states[$name] = $name;
        }

        $choiceQuestion = new ChoiceQuestion(
            "Passer l'utilisateur à l'épage : ",
            $states
        );
        $state          = $helper->ask($input, $output, $choiceQuestion);
        if (!$workflow->can($entity, $state)) {
            $inputOutput->warning(
                ['Action impossible']
            );

            return;
        }

        $workflow->apply($entity, $state);
        $this->entityManager->flush();
        $inputOutput->success('Utilisateur passé au stade "'.$state.'"');
    }

    /**
     * @return array<int|string, string>
     */
    protected function tableQuestionUser(): array
    {
        $users = $this->userRepository->findBy([], ['username' => 'ASC']);
        $table = [];
        foreach ($users as $user) {
            // @var User $user
            $table[$user->getUsername()] = json_encode(
                [
                    'username' => $user->getUsername(),
                    'email'    => $user->getEmail(),
                    'groupe'   => $user->getRefgroupe()->getName(),
                    'state'    => $user->getState(),
                ],
                JSON_THROW_ON_ERROR
            );
        }

        return $table;
    }

    protected function updatePassword($helper, $username, $inputOutput, InputInterface $input, OutputInterface $output): void
    {
        $entity = $this->userRepository->findOneBy(['username' => $username]);
        if (!$entity instanceof User || is_null($entity)) {
            $inputOutput->warning(
                ['Utilisateur introuvable']
            );

            return;
        }

        $question = new Question("Entrer le password de l'utilisateur : ");
        $question->setHidden(true);

        $password1 = $helper->ask($input, $output, $question);
        $question  = new Question("Resaisir le password de l'utilisateur : ");
        $question->setHidden(true);

        $password2 = $helper->ask($input, $output, $question);
        if ($password1 !== $password2) {
            $inputOutput->error('Mot de passe incorrect');

            return;
        }

        $old = clone $entity;
        $entity->setPlainPassword($password1);
        $this->userRequestHandler->handle($old, $entity);
        $inputOutput->success('Mot de passe changé');
    }
}
