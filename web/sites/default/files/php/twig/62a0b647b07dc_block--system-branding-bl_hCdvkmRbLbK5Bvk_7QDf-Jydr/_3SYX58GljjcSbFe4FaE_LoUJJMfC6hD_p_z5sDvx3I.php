<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* themes/custom/stchk34_subtheme/templates/block--system-branding-block.html.twig */
class __TwigTemplate_1637db9525aeb938656b5d33f57540587e4aed9a0cb8bbcf7c37302f5f9ba1e0 extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 15
        $context["attributes"] = twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [0 => "site-branding"], "method", false, false, true, 15);
        // line 16
        echo "  ";
        if ((($context["site_logo"] ?? null) || ($context["site_name"] ?? null))) {
            // line 17
            echo "    <div class=\"d-inline-flex flex-column justify-content-center align-items-center site-branding-block\">
      <a class=\"site-branding__name\">
        ";
            // line 19
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["site_name"] ?? null), 19, $this->source), "html", null, true);
            echo "
      </a>
      <div class=\"site-branding__slogan\">
        ";
            // line 22
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["site_slogan"] ?? null), 22, $this->source), "html", null, true);
            echo "
      </div>
    </div>
  ";
        }
        // line 26
        echo "   
      ";
        // line 27
        if (($context["site_logo"] ?? null)) {
            // line 28
            echo "        <img src=\"";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["site_logo"] ?? null), 28, $this->source), "html", null, true);
            echo "\" alt=\"";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Home"));
            echo "\" class=\"img-logo d-inline-block align-top\" />
      ";
        }
    }

    public function getTemplateName()
    {
        return "themes/custom/stchk34_subtheme/templates/block--system-branding-block.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  66 => 28,  64 => 27,  61 => 26,  54 => 22,  48 => 19,  44 => 17,  41 => 16,  39 => 15,);
    }

    public function getSourceContext()
    {
        return new Source("{#
/**
 * @file
 * Bootstrap Barrio's theme implementation for a branding block.
 *
 * Each branding element variable (logo, name, slogan) is only available if
 * enabled in the block configuration.
 *
 * Available variables:
 * - site_logo: Logo for site as defined in Appearance or theme settings.
 * - site_name: Name for site as defined in Site information settings.
 * - site_slogan: Slogan for site as defined in Site information settings.
 */
#}
{% set attributes = attributes.addClass('site-branding') %}
  {% if site_logo or site_name %}
    <div class=\"d-inline-flex flex-column justify-content-center align-items-center site-branding-block\">
      <a class=\"site-branding__name\">
        {{ site_name }}
      </a>
      <div class=\"site-branding__slogan\">
        {{ site_slogan }}
      </div>
    </div>
  {% endif %}
   
      {% if site_logo %}
        <img src=\"{{ site_logo }}\" alt=\"{{ 'Home'|t }}\" class=\"img-logo d-inline-block align-top\" />
      {% endif %}
", "themes/custom/stchk34_subtheme/templates/block--system-branding-block.html.twig", "/var/www/web/themes/custom/stchk34_subtheme/templates/block--system-branding-block.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("set" => 15, "if" => 16);
        static $filters = array("escape" => 19, "t" => 28);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['set', 'if'],
                ['escape', 't'],
                []
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}
