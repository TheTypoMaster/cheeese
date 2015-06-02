<?php
    namespace Main\CommunityBundle\EventListener;

    use Avanzu\AdminThemeBundle\Event\ShowUserEvent;
    use MyAdminBundle\Model\UserModel;

    class MyShowUserListener {

        public function onShowUser(ShowUserEvent $event) {

            $user = $this->getUser();
            $event->setUser($user);

        }

        protected function getUser() {
            // retrieve your concrete user model or entity
        }

    }