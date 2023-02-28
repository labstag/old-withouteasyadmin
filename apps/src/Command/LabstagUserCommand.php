<?php

namespace Labstag\Command;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\Groupe;
use Labstag\Entity\User;
use Labstag\Form\Admin\Paragraph\Post\UserType;
use Labstag\Lib\CommandLib;
use Labstag\Repository\GroupeRepository;
use Labstag\Repository\UserRepository;
use Labstag\RequestHandler\UserRequestHandler;
use Labstag\Service\RepositoryService;
use Labstag\Service\WorkflowService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'labstag:user')]
class LabstagUserCommand extends CommandLib
{
    public function __construct(
        RepositoryService $repositoryService,
        EntityManagerInterface $entityManager,
        protected WorkflowService $workflowService,
        protected UserRequestHandler $userRequestHandler,
        protected GroupeRepository $groupeRepository,
        protected UserRepository $userRepository
    )
    {
        parent::__construct($repositoryService, $entityManager);
    }

    protected function actionEnableDisableDelete(
        InputInterface $input,
        OutputInterface $output,
        SymfonyStyle $symfonyStyle,
        string $action
    ): void
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
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
                    $this->enable($helper, $username, $symfonyStyle, $input, $output);

                    break;
                case 'disable':
                    $this->disable($helper, $username, $symfonyStyle, $input, $output);

                    break;
                case 'delete':
                    $this->delete($helper, $username, $symfonyStyle, $input, $output);

                    break;
            }
        }
    }

    protected function actionState(InputInterface $input, OutputInterface $output, SymfonyStyle $symfonyStyle): void
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
        $choiceQuestion = new ChoiceQuestion(
            "Entrer le username de l'utilisateur : ",
            $this->tableQuestionUser()
        );
        $username = $helper->ask($input, $output, $choiceQuestion);
        $this->state($helper, $username, $symfonyStyle, $input, $output);
    }

    protected function actionUpdatePassword(
        InputInterface $input,
        OutputInterface $output,
        SymfonyStyle $symfonyStyle
    ): void
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
        $choiceQuestion = new ChoiceQuestion(
            "Entrer le username de l'utilisateur : ",
            $this->tableQuestionUser()
        );
        $username = $helper->ask($input, $output, $choiceQuestion);
        $this->updatePassword($helper, $username, $symfonyStyle, $input, $output);
    }

    protected function configure(): void
    {
        $this->setDescription('command for admin user');
    }

    protected function create(
        QuestionHelper $questionHelper,
        SymfonyStyle $symfonyStyle,
        InputInterface $input,
        OutputInterface $output
    ): void
    {
        $symfonyStyle = new SymfonyStyle($input, $output);
        $user = new User();
        $old = clone $user;
        $question = new Question("Entrer le username de l'utilisateur : ");
        $username = $questionHelper->ask($input, $output, $question);
        $user->setUsername($username);
        $question = new Question("Entrer le password de l'utilisateur : ");
        $question->setHidden(true);

        $password1 = $questionHelper->ask($input, $output, $question);
        $question = new Question("Resaisir le password de l'utilisateur : ");
        $question->setHidden(true);

        $password2 = $questionHelper->ask($input, $output, $question);
        if ($password1 !== $password2) {
            $symfonyStyle->error('Mot de passe incorrect');

            return;
        }

        $user->setPlainPassword($password1);
        $question = new Question("Entrer l'email de l'utilisateur : ");
        $email = $questionHelper->ask($input, $output, $question);
        $user->setEmail($email);
        $groupes = $this->groupeRepository->findBy([], ['name' => 'DESC']);
        $data = [];
        foreach ($groupes as $groupe) {
            // @var Groupe $groupe
            if ('visiteur' == $groupe->getCode()) {
                continue;
            }

            $data[$groupe->getCode()] = $groupe->getName();
        }

        $question = new ChoiceQuestion(
            "Groupe à attribuer à l'utilisateur",
            $data
        );
        $selection = $questionHelper->ask($input, $output, $question);
        foreach ($groupes as $groupe) {
            // @var Groupe $groupe
            if ($selection != $groupe->getCode()) {
                continue;
            }

            $user->setRefgroupe($groupe);
        }

        $this->userRequestHandler->handle($old, $user);
        $symfonyStyle->success('Utilisateur ajouté');
    }

    protected function delete(
        QuestionHelper $questionHelper,
        string $username,
        SymfonyStyle $symfonyStyle,
        InputInterface $input,
        OutputInterface $output
    ): void
    {
        $entity = $this->userRepository->findOneBy(['username' => $username]);
        if (!$entity instanceof UserType) {
            $symfonyStyle->warning(
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

        $action = $questionHelper->ask($input, $output, $choiceQuestion);
        if ('oui' !== $action) {
            return;
        }

        $old = clone $entity;
        $this->userRepository->remove($entity);
        $this->userRequestHandler->handle($old, $entity);
        $symfonyStyle->success('Utilisateur supprimé');
    }

    protected function disable(
        QuestionHelper $questionHelper,
        string $username,
        SymfonyStyle $symfonyStyle,
        InputInterface $input,
        OutputInterface $output
    ): void
    {
        $entity = $this->userRepository->findOneBy(['username' => $username]);
        if (!$entity instanceof User) {
            $symfonyStyle->warning(
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

        $action = $questionHelper->ask($input, $output, $choiceQuestion);
        if ('oui' !== $action || !$this->workflowService->has($entity)) {
            $symfonyStyle->warning(
                ['Action impossible']
            );

            return;
        }

        $workflow = $this->workflowService->get($entity);
        if (!$workflow->can($entity, 'desactiver')) {
            $symfonyStyle->warning(
                ['Action impossible']
            );

            return;
        }

        $old = clone $entity;
        $workflow->apply($entity, 'desactiver');
        $this->entityManager->flush();
        $this->userRequestHandler->handle($old, $entity);
        $symfonyStyle->success('Utilisateur désactivé');
    }

    protected function enable(
        QuestionHelper $questionHelper,
        string $username,
        SymfonyStyle $symfonyStyle,
        InputInterface $input,
        OutputInterface $output
    ): void
    {
        $entity = $this->userRepository->findOneBy(['username' => $username]);
        if (!$entity instanceof User) {
            $symfonyStyle->warning(
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

        $action = $questionHelper->ask($input, $output, $choiceQuestion);
        if ('oui' !== $action || !$this->workflowService->has($entity)) {
            $symfonyStyle->warning(
                ['Action impossible']
            );

            return;
        }

        $workflow = $this->workflowService->get($entity);
        if (!$workflow->can($entity, 'activer')) {
            $symfonyStyle->warning(
                ['Action impossible']
            );

            return;
        }

        $old = clone $entity;
        $workflow->apply($entity, 'activer');
        $this->entityManager->flush();
        $this->userRequestHandler->handle($old, $entity);
        $symfonyStyle->success('Utilisateur activé');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
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
                'cancel'         => 'cancel',
            ]
        );

        $action = (string) $helper->ask($input, $output, $choiceQuestion);
        match ($action) {
            'list' => $this->list($symfonyStyle, $output),
            'create' => $this->create($helper, $symfonyStyle, $input, $output),
            'updatepassword' => $this->actionUpdatePassword($input, $output, $symfonyStyle),
            'state' => $this->actionState($input, $output, $symfonyStyle),
            'enable', 'disable', 'delete' => $this->actionEnableDisableDelete($input, $output, $symfonyStyle, $action),
            'cancel' => $output->writeln('cancel'),
            default => $output->writeln('Action inconnue'),
        };

        return Command::SUCCESS;
    }

    protected function list(SymfonyStyle $symfonyStyle, OutputInterface $output): void
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

        $symfonyStyle->table(
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

    protected function state(
        QuestionHelper $questionHelper,
        string $username,
        SymfonyStyle $symfonyStyle,
        InputInterface $input,
        OutputInterface $output
    ): void
    {
        $entity = $this->userRepository->findOneBy(['username' => $username]);
        if (!$entity instanceof User) {
            $symfonyStyle->warning(
                ['Utilisateur introuvable']
            );

            return;
        }

        $states = [];
        $workflow = $this->workflowService->get($entity);
        $transitions = $workflow->getEnabledTransitions($entity);
        foreach ($transitions as $transition) {
            $name = $transition->getName();
            $states[$name] = $name;
        }

        $choiceQuestion = new ChoiceQuestion(
            "Passer l'utilisateur à l'épage : ",
            $states
        );
        $state = $questionHelper->ask($input, $output, $choiceQuestion);
        if (!$workflow->can($entity, $state)) {
            $symfonyStyle->warning(
                ['Action impossible']
            );

            return;
        }

        $workflow->apply($entity, $state);
        $this->entityManager->flush();
        $symfonyStyle->success('Utilisateur passé au stade "'.$state.'"');
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

    protected function updatePassword(
        QuestionHelper $questionHelper,
        string $username,
        SymfonyStyle $symfonyStyle,
        InputInterface $input,
        OutputInterface $output
    ): void
    {
        $entity = $this->userRepository->findOneBy(['username' => $username]);
        if (!$entity instanceof User) {
            $symfonyStyle->warning(
                ['Utilisateur introuvable']
            );

            return;
        }

        $question = new Question("Entrer le password de l'utilisateur : ");
        $question->setHidden(true);

        $password1 = $questionHelper->ask($input, $output, $question);
        $question = new Question("Resaisir le password de l'utilisateur : ");
        $question->setHidden(true);

        $password2 = $questionHelper->ask($input, $output, $question);
        if ($password1 !== $password2) {
            $symfonyStyle->error('Mot de passe incorrect');

            return;
        }

        $old = clone $entity;
        $entity->setPlainPassword($password1);
        $this->userRequestHandler->handle($old, $entity);
        $symfonyStyle->success('Mot de passe changé');
    }
}
