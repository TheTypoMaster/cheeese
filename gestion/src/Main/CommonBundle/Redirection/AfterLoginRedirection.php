<?php
namespace Main\CommonBundle\Redirection;
 
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;
use Symfony\Component\Security\Http\HttpUtils;
use Main\CommonBundle\Services\Session\ServiceSession;

 
class AfterLoginRedirection extends DefaultAuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    protected $router;

    protected $session;

    protected $httpUtils;

    protected $options;
 
    /**
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router, ServiceSession $serviceSession, HttpUtils $httpUtils,$options = null)
    {
        $this->router = $router;
        $this->session = $serviceSession;
        $this->httpUtils = $httpUtils;
        $this->options = $options;
    }
 
    /**
     * @param Request $request
     * @param TokenInterface $token
     * @return RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        // On récupère la liste des rôles d'un utilisateur
        $roles = $token->getRoles();
        // On transforme le tableau d'instance en tableau simple
        $rolesTab = array_map(function($role){ 
          return $role->getRole(); 
        }, $roles);
        // S'il s'agit d'un admin ou d'un super admin on le redirige vers le backoffice
        if (in_array('ROLE_PARTICULIER', $rolesTab, true)) {
        	if ($this->session->getDesiredDevis() != null) {
        		return new RedirectResponse($this->router->generate('devis_show', array(
        		'id' => $this->session->getDesiredDevis()
        		)
        		));
        	}else{
                return $this->httpUtils->createRedirectResponse($request, $this->determineTargetUrl($request));
                }
        }
    }
}